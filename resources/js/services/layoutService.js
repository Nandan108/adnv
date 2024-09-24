import { ref } from 'vue'
import { castArray } from 'lodash';

export const successMessages = ref([])
export const errorMessages = ref([])

export function loadMessages(newFlash) {
  if (!newFlash) return;
  console.debug('loadMessage(): new flash', newFlash);
  // move any success and error messages from session flash to display
  successMessages.value.push(...castArray(newFlash.success || []));
  newFlash.success = null;
  errorMessages.value.push(...castArray(newFlash.error || []));
  newFlash.error = null;
}

export function dismissMessage(type, idx) {
  switch (type) {
    case 'success': successMessages.value.splice(idx, 1); break;
    case 'error': errorMessages.value.splice(idx, 1); break;
  }
}
