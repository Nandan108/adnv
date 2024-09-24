import { computed } from 'vue';

import { Model } from 'pinia-orm';

function createModelClass(entityName, pk, fields) {
  return class extends Model {
    static entity = entityName;
    static primaryKey = pk;


    static fields() {
      fields[pk] ??= { type: 'string', default: '' };

      return {
        ...Object.entries(fields).reduce((acc, [fieldname, fieldConfig]) => {
          acc[fieldname] = this[fieldConfig.type](fieldConfig.default);
          return acc;
        }, {}),
      };
    }
  };
}

const lookups = {};

function makeMapModel(entity, keyName, valueName, valueType = 'string') {
  const modelClass = createModelClass(entity, keyName, {
      [valueName]: { type: 'string', default: null }
  });
  const makeRecords = (map) => {
    const entries = Object.entries(map)
    return entries.map(([key, val]) => ({ [keyName]: key, [valueName]: val}));
  }
  return { entity, modelClass, makeRecords }
}

function createLookup(entity, key, val) {
  try {
    return lookups[entity] = makeMapModel(entity, key, val);
  } catch (e) { console.error(e); }
}

export { createModelClass, lookups, makeMapModel, createLookup };

// Object.keys(lookups).forEach(entity => {
//   try {
//     Object.assign(lookups[entity], { entity }, makeMapModel(entity, 'key', 'val'));
//   } catch (e) { console.error(e); }
// })
