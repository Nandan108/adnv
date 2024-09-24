<style scoped>
h2 {
  @apply text-2xl font-bold;
}
</style>
<style>
  .dtr-control {
    @apply bg-slate-200 cursor-pointer hover:bg-slate-300 text-center transition-all duration-300
  }
  .dt-rowReorder-moving .dtr-control, .dt-rowReorder-moving {
    @apply !bg-slate-400 !cursor-move
  }
</style>
<template>
  <div class="mt-8 flex flex-col">
    <div class="flex justify-between -mb-4">
      <h2>{{ title }}</h2>
      <div class="flex gap-4 z-10">
        <PrimaryButton @click="openNew()">+<span class="hidden md:block"> Ajouter</span></PrimaryButton>
        <PrimaryButton :disabled="!selectedItem" @click="openSelectedForEdits()">✎<span class="hidden md:block"> Modifier</span></PrimaryButton>
        <DangerButton :disabled="!selectedItem" @click="deleteSelectedItem()">🗑<span class="hidden md:block"> Supprimer</span></DangerButton>
      </div>
    </div>

    <DataTable :id='dtId' ref='tableRef' class="display" :data='data' :columns="config.columns" :options="config.options"
      @select="onSelect()" @deselect="onSelect()" @row-reordered="handleRowReordered">
    </DataTable>

    <Modal :title="title" :visible="modalVisible" @submit="saveChanges()" @close="close()"
      class=' max-h-lvh overflow-y-auto'>
      <slot v-if="modalModel" :model="modalModel"></slot>
    </Modal>
  </div>
</template>

<script setup>

import { format } from 'date-fns'
import { fr } from 'date-fns/locale'
import { ref, onMounted, computed, reactive, nextTick } from 'vue';
import { Link } from '@inertiajs/vue3'
import { useRepo } from 'pinia-orm';
import { merge } from 'lodash';
import Modal from 'Components/UI/Modal.vue';
import DangerButton from 'Components/UI/DangerButton.vue';
import PrimaryButton from 'Components/UI/PrimaryButton.vue';

import DataTable from 'datatables.net-vue3';
import { genericCrudApi } from 'services/apiService';

const props = defineProps({
  document: Object,
  classRef: Function,
  title: String,
  data: Array,
  dtId: String,
  dtConfig: Object,
});

const repo = useRepo(props.classRef);

const config = computed(() => {
  const merged = merge(
    {
      columns: [],
      options: {
        paging: false,
        rowReorder: { dataSrc: 'ord' },
        layout: { topEnd: {} },
        responsive: true,
        select: true
      }
    },
    props.dtConfig
  );
  merged.columns ??= [];
  merged.columns.unshift({
    title: '', data: 'ord', visible: true, width: '3.5em', align: 'center',
    render: (ord) => ' '+ord+" ⇕", /*
    render: (ord) => ord+"⇕", //*/
  })
  return merged;
})

const modalVisible = ref(false);
const selectedRows = ref([]);
const tableRef = ref(null);

const dataTable = computed(() => tableRef.value?.dt);

function onSelect() {
  const rows = dataTable.value.rows({ selected: true });
  selectedRows.value = rows.data().toArray();
}

const selectedItem = computed(() => {
  const item = selectedRows.value[0];
  return item ? (item.clone ? item.clone() : { ...item }) : null;
});

const modalModel = ref(repo.make(props.classRef));

function openNew() {
  modalModel.value = repo.make();
  console.log(`Opening modal for ${props.classRef.name}`);
  modalVisible.value = true;
};

function openSelectedForEdits() {
  if (!this.selectedItem) return;
  modalModel.value = this.selectedItem;
  modalVisible.value = true;
}

function close() {
  modalVisible.value = false;
}

function saveChanges() {
  genericCrudApi.update(modalModel.value, props.document);
  this.close();
}

function deleteSelectedItem() {
  this.selectedRows.forEach(row => genericCrudApi.delete(row, props.document));
}

const handleRowReordered = ({ dt }, changedNodes) => {
  // const { detail } = event;
  // const reorderedData = detail.triggerRow;
  //changedNodes.forEach(({ oldPosition, newPosition }) => { })
  const rows = dataTable.value.rows().data().toArray();

  const rowsInNewOrder = changedNodes.reduce((acc, { newPosition, oldPosition }) => {
    acc[newPosition] = rows[oldPosition];
    return acc;
  }, [...rows]);

  const IDs = rowsInNewOrder.map(row => row.id);
  console.log('IDs in new order', IDs);

  // const IDsInOrder = cgedOrder.map(i => rows[i].id);

  // console.log('Row reordered:', Object.fromEntries(changedNodes.map(({ newPosition, oldPosition }) => ([oldPosition, newPosition]))));
  const classRef = rows[0].constructor;


  genericCrudApi.updateOrder(props.document, IDs, classRef);

  // const order = ;
  // console.log('Reorder - after', allRows.map(r => r.id));
  // nextTick(() => console.log('Rows', allRows.map(r => r.id)))
  // dataTable.value.rows().foreach();

  // Handle the reorder event here, e.g., update the backend with new order
};

</script>