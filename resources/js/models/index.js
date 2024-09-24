import { Model, useRepo } from 'pinia-orm';
import * as allModels from './models';

export const models = allModels;

export * from './genericLookups';

export function loadData(data) {
  if (!data) return;
  console.debug('loadData(): Ready to load', data)
  // data is sent to client
  // Object.entries(models).forEach(([className, classRef]) => {
  //   const classData = data[className] || [];
  return Object.fromEntries(Object.entries(data).map(([className, data]) => {
    const classRef = models[className];
    if (!classRef) {
      console.warn(`Model/Repo "${className}" is not defined. Can't load data!`, { data });
      return;
    }

    if (data.length) {
      console.debug(`Will now insert ${data.length} records into ${className}`, data);
      try {
        const inserted = useRepo(classRef).save(data);
        console.debug(`Inserted`, inserted);
        return [className, inserted];
      } catch (e) {
        console.error('Error while trying to insert ' + className, e);
      }
    }
  }))
}
