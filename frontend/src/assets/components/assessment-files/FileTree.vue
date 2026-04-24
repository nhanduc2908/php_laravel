<template>
    <div class="file-tree"><div class="tree-header"><i class="fas fa-folder-open"></i><span>Thư mục tài liệu</span><button class="refresh-btn" @click="refresh"><i class="fas fa-sync-alt"></i></button></div>
    <div class="tree-body"><div v-for="node in treeData" :key="node.id" class="tree-node"><div class="node-header" @click="toggleNode(node.id)"><i class="node-icon" :class="expandedNodes.includes(node.id) ? 'fas fa-folder-open' : 'fas fa-folder'"></i><span class="node-name">{{ node.name }}</span><span class="node-count">{{ node.file_count }}</span><i class="node-arrow fas fa-chevron-right" :class="{ rotated: expandedNodes.includes(node.id) }"></i></div>
    <div v-show="expandedNodes.includes(node.id)" class="node-children"><FileCard v-for="file in node.files" :key="file.id" :file="file" @click="selectFile(file)" /></div></div></div></div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import FileCard from './FileCard.vue'

const emit = defineEmits(['select'])
const treeData = ref([])
const expandedNodes = ref([])

const toggleNode = (id) => { const idx = expandedNodes.value.indexOf(id); idx > -1 ? expandedNodes.value.splice(idx, 1) : expandedNodes.value.push(id) }
const selectFile = (file) => emit('select', file)
const refresh = () => fetchTree()
const fetchTree = async () => { const res = await fetch('/api/v1/assessment-files/tree'); treeData.value = (await res.json()).data || [] }
onMounted(fetchTree)
</script>

<style scoped>
.file-tree { background: var(--card-bg); border-radius: 12px; overflow: hidden; }
.tree-header { display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-bottom: 1px solid var(--border-color); font-weight: 600; }
.refresh-btn { margin-left: auto; background: none; border: none; cursor: pointer; color: var(--text-secondary); }
.tree-body { padding: 8px 0; }
.tree-node { margin-bottom: 4px; }
.node-header { display: flex; align-items: center; gap: 8px; padding: 8px 12px; cursor: pointer; transition: background 0.2s; }
.node-header:hover { background: var(--bg-secondary); }
.node-icon { color: #f59e0b; }
.node-name { flex: 1; }
.node-count { font-size: 12px; color: var(--text-secondary); background: var(--bg-secondary); padding: 2px 6px; border-radius: 12px; }
.node-arrow { font-size: 12px; transition: transform 0.2s; }
.node-arrow.rotated { transform: rotate(90deg); }
.node-children { margin-left: 28px; padding-left: 12px; border-left: 1px dashed var(--border-color); }
</style>