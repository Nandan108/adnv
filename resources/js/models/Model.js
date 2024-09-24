import { computed } from 'vue';

export default class Model {
  static store = null;
  static collectionName = null;
  static byPK = null;
  static primaryKey = 'id'; // Default primary key
  static by = {}; // Object to hold dictionaries
  static belongsTo = {}; // Map of relationship names to { lk, class, fk } pairs
  static hasOne = {}; // Map of relationship names to { fk, class, lk } pairs
  static hasMany = {}; // Map of relationship names to { fk, class, lk } pairs
  static getters = {}; // Map of computed properties
  static modelClasses = {}; // Map of model classes

  constructor(data, instanceStore = null) {
    Object.assign(this, data);
    this._instanceStore = instanceStore;

    // Setup computed getters defined in the static 'getters' property
    if (this.constructor.getters) {
      Object.entries(this.constructor.getters).forEach(([getterName, getterFn]) => {
        const comp = computed(() => getterFn.call(this));
        Object.defineProperty(this, getterName, { get: () => comp.value });
      });
    }
  }

  static setStore(store) {
    this.store = store;
  }

  get store() {
    return this._instanceStore || this.constructor.store;
  }

  static getInstance(data) {
    return data instanceof this ? data : new this(data);
  }

  // Helper method to get or create a dictionary based on class and key
  static getDictionary(key = null, unique = false) {
    const localDicKey = key || this.PK;
    if (!this.by[localDicKey]) {
      unique ||= localDicKey === this.PK; // Automatically set unique to true for PK
      const storeDicKey = `${this.collectionName}_by_${unique ? 'UK_' : ''}${localDicKey}`;

      const reducer = unique
        ? (acc, item) => { acc[item[localDicKey]] = item; return acc; }
        : (acc, item) => { (acc[item[localDicKey]] ??= []).push(item); return acc; };
      const defaultVal = unique ? null : [];

      const computedDictionary = computed(() =>
        this.store[this.collectionName].reduce(reducer, {})
      );
      // in the store, we set the dictionary in the state where it's unpacked as needed
      this.store.$state[storeDicKey] = computedDictionary;
      // here we return a function that unpacks it
      this.by[localDicKey] = (key) => computedDictionary.value[key] ?? defaultVal;
    }
    return this.by[localDicKey];
  }

  static registerClasses(store, classes) {
    // keep a map of all model classes
    classes.forEach(c => this.modelClasses[c.name] = c);

    // resolve class names into class references in relationship declarations
    classes.flatMap(c => [c.belongsTo, c.hasMany, c.hasOne])
      .flatMap(relationships => Object.values(relationships))
      .filter(relationDef => typeof relationDef.class === 'string')
      .forEach(relationDef => relationDef.class = this.modelClasses[relationDef.class])

    // Register classes with the store
    classes.forEach(ModelClass => ModelClass.register(store));

    // Setup associations
    classes.forEach(ModelClass => ModelClass.setupAssociations());
  }

  // Register method to set up the model in the store
  static register(store) {
    this.collectionName = this.name.toLowerCase() + 's';
    this.setStore(store);

    store[this.collectionName] ??= [];
    store[this.collectionName].modelClass = this;

    // Set up the dictionary for O(1) access using the primary key
    this.byPK = this.getDictionary();
    console.log(`${this.name}.byPK = ${this.byPK}`);
  }

  static createRelationGetter(relationName, dictionary, localKey, filter = null) {
    if (!Object.prototype.hasOwnProperty.call(this.prototype, relationName)) {
      Object.defineProperty(this.prototype, relationName, {
        get() {
          const val = dictionary(this[localKey]);
          return filter ? filter(val, this[localKey]) ?? null : val;
        },
      });
    }
  }

  static setupBelongsToRelationships(relationships) {
    Object.entries(relationships).forEach(([relationName, { lk, class: RelatedClass, fk }]) => {
      fk ??= RelatedClass.PK;

      if (!RelatedClass) {
        console.error(`Related class is not defined for the relationship ` +
            `'${relationName}' in class '${this.name}'.`);
        return;
      }
      if (!lk) {
        console.error(`Mandatory local key (lk) is not defined for the ` +
            `relationship '${relationName}' in class '${this.name}'.`);
        return;
      }

      // Get or create the dictionary used by this relation
      const dic = RelatedClass.getDictionary(fk, true);
      // Define the belongsTo getter on the prototype level
      this.createRelationGetter(relationName, dic, lk, (val, key) => {
        if (val === null) {
          console.warn(
            `class ${this.name}: item ${RelatedClass.name}[${key}] not found! ` +
            `Check if the foreign key ${RelatedClass.name}.${fk} matches ` +
            `the local key ${this.name}.${lk}.`
          );
          console.info(`${RelatedClass.name} currently loaded`, this.store[RelatedClass.collectionName].length)
        }
        return val;
      })
    });
  }

  static setupHasRelationships(relationships, hasOne) {
    Object.entries(relationships).forEach(([relationName, { fk, class: RelatedClass, lk }]) => {
      if (!RelatedClass) {
        console.error(`Related class is not defined for the relationship '${relationName}' in class '${this.name}'.`);
        return;
      }
      if (!fk) {
        console.error(`Mandatory foreign key (fk) is not defined for the relationship '${relationName}' in class '${this.name}'.`);
        return;
      }
      // Get or create the dictionary based on the foreign key in the related class
      const dictionaryComputed = RelatedClass.getDictionary(fk, false);
      // If it's a hasOne relationship, unpack the single element
      const filter = hasOne ? (val) => val[0] : null;

      // Define the hasMany getter on the prototype level
      this.createRelationGetter(relationName, dictionaryComputed, lk ?? this.PK, filter)
    });
  }

  // Setup method to initialize belongsTo and hasMany relationships and prototype getters
  static setupAssociations() {
    this.setupBelongsToRelationships(this.belongsTo);
    this.setupHasRelationships(this.hasMany);
    this.setupHasRelationships(this.hasOne, true);
  }

  // Load method to update or create instances
  static load(dataArray) {
    dataArray.forEach(data => {
      const existingInstance = this.byPK(data[this.PK]);

      if (existingInstance) {
        // Update the existing instance
        Object.assign(existingInstance, data);
      } else {
        // Create a new instance and add it to the collection
        const newInstance = this.getInstance(data);
        this.store[this.collectionName].push(newInstance);
      }
    });
    console.log(`${this.collectionName} contains: `, this.store[this.collectionName].length);
  }

  static loadCollections(collectionsByName) {
    Object.values(this.modelClasses)
      .forEach(classRef => {
        const items = collectionsByName[classRef.collectionName] ?? [];
        if (!items) return;
        if (items.length) {
          console.log(`Ready to load ${items.length} items into ${classRef.collectionName}`);
          classRef.load(items);
        } else {
          console.log(`No items to load into ${classRef.collectionName}`);
        }
      })
  }
}
