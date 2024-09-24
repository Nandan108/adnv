import axios from 'axios';
import { loadData, models } from 'models';
import { Model, useRepo } from 'pinia-orm';
import { ref } from 'vue';
import { successMessages, errorMessages } from 'services/layoutService';
import { loadMessages } from 'services/layoutService';

const API_URL = 'https://api.example.com';

export const apiUpdating = new ref(false);

export async function updateTraveler(updateData, bookingHashId, fullIdx) {
  const travelers = useRepo(models.Traveler);

  try {
    apiUpdating.value = true;
    const url = route('reservation.traveler.update', [bookingHashId, fullIdx]);
    const response = await axios.put(url, updateData)
      .finally(() => {
        apiUpdating.value = false;
      });

    const updatedTraveler = loadData(response.data)['Traveler'][0];

    return updatedTraveler;
    //travelers.insert([Traveler.getInstance(updatedTraveler)]);
  } catch (error) {
    console.error('Update failed:', error);
    apiUpdating.value = false;
    throw error; // Propagate the error
  }
}

const commercialdocInfoRoutes = {
  create: (doc) => ['post', route('commercialdoc.info.store', doc.hashId)],
  update: (doc, item) => ['put', route('commercialdoc.info.update', [doc.hashId, item.id])],
  delete: (doc, item) => ['delete', route('commercialdoc.info.destroy', [doc.hashId, item.id])],
  reorder: (doc) => ['put', route('commercialdoc.info.reorder', doc.hashId)],
};

function showError(action, axiosError) {
  const errors = axiosError?.response?.data?.errors;
  const messages = errors
    ? Object.values(errors).map(([v]) => v)
    : [axiosError?.response?.data?.message || axiosError.message];
  console.error(`Failed to ${action}`, messages);
  errorMessages.value.push(...messages);
}

export const genericCrudApi = {
  routes: {
    [models.CommercialdocItem.entity]: {
      create: (doc) => ['post', route('commercialdoc.item.store', doc.hashId)],
      update: (doc, item) => ['put', route('commercialdoc.item.update', [doc.hashId, item.id])],
      delete: (doc, item) => ['delete', route('commercialdoc.item.destroy', [doc.hashId, item.id])],
      reorder: (doc) => ['put', route('commercialdoc.item.reorder', doc.hashId)],
    },
    [models.CommercialdocFlightLine.entity]: commercialdocInfoRoutes,
    [models.CommercialdocHotelLine.entity]: commercialdocInfoRoutes,
    [models.CommercialdocHeaderResId.entity]: commercialdocInfoRoutes,
    [models.CommercialdocTransferLine.entity]: commercialdocInfoRoutes,
    [models.CommercialdocTransferComments.entity]: commercialdocInfoRoutes,
    [models.CommercialdocTravelerLine.entity]: commercialdocInfoRoutes,
    [models.CommercialdocTripInfo.entity]: commercialdocInfoRoutes,
  },

  /**
   * Call api action on a Model
   * @param {string} action - The action to execute (create, update or delete)
   * @param {Model} model - The data for the new CommercialdocItem.
   * @returns {Promise<models.CommercialdocItem>} - The created CommercialdocItem.
   */
  async act(action, model, parent) {
    const itemRoutes = this.routes[model.$entity()];
    if (!itemRoutes) {
      console.error(`API routes not defined for ${model.$entity()}`);
      return null;
    }
    if (!itemRoutes[action]) {
      console.error(`Route "${action}" not defined for ${model.$entity()}`);
      return null;
    }

    const [method, url] = itemRoutes[action](parent, model);

    console.debug(`API executing '${action}' = [${method} ${url}] on model: `, model);

    return await axios[method](url, model);
  },

  async updateOrder(doc, IDs, classRef) {
    const reorderRouteGetter = this.routes[classRef.entity]?.reorder;
    if (!reorderRouteGetter) {
      console.error(`Route "reorder" not defined for ${classRef.entity}`);
      return null;
    }

    const [method, url] = reorderRouteGetter(doc);
    const data = { IDs };

    console.debug(`API executing 'reorder' = [${method} ${url}] on ${classRef.entity}`, data);

    try {
      const response = await axios[method](url, data);
      loadData(response.data);
    } catch (error) {
      showError('reorder', error)
    }
  },

  /**
   * Create a new CommercialdocItem.
   * @param {number} docId - The ID of the Commercialdoc to which the item belongs.
   * @param {object} data - The data for the new CommercialdocItem.
   * @returns {Promise<models.CommercialdocItem>} - The created CommercialdocItem.
   */
  async create(model, parent) {
    try {
      const response = await this.act('create', model, parent);
      loadData(response.data)
    } catch (error) {
      showError('create', error)
    }
  },

  /**
   * Update an existing CommercialdocItem.
   * @param {Model} model - The model to api-act upon
   * @returns {Promise<models.CommercialdocItem>} - The updated CommercialdocItem.
   */
  async update(model, parent) {
    // if the model has no primary key, then we need to create it, not update it.
    if (!model.$getKey()) return this.create(model, parent);

    try {
      console.log('API updating changes for:', model);

      const response = await this.act('update', model, parent);
      const inserted = loadData(response.data)
      console.log('inserted', Object.values(inserted)[0][0]);

    } catch (error) {
      showError('update', error)
    }
  },

  /**
   * Delete an existing CommercialdocItem.
   * @param {Model} model - The model to api-act upon
   * @returns {Promise<void>} - Resolves when the item is deleted.
   */
  async delete(model, parent) {
    try {
      await this.act('delete', model, parent)
        .then(() => {
          useRepo(model.constructor).destroy(model.$getKey());
        })
        .catch((error) => {
          console.error(error);
          errorMessages.value.push(error);
        });
    } catch (error) {
      showError('delete', error)
    }

  },
};

/**
 * Receives a data object from a call to useForm, and "digests"
 * messages and data from it.
 * @param {*} page
 */
export function onSuccess(page) {
  loadData(page?.props?.data);
  loadMessages(page?.props?.flash);
}

