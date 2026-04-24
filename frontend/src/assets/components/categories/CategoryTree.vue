<template>
    <div class="category-tree">
        <div class="tree-header">
            <h3>Danh mục tiêu chí (17)</h3>
            <div class="tree-actions">
                <button class="btn-icon" @click="expandAll" title="Mở rộng tất cả">
                    <i class="fas fa-expand-alt"></i>
                </button>
                <button class="btn-icon" @click="collapseAll" title="Thu gọn tất cả">
                    <i class="fas fa-compress-alt"></i>
                </button>
                <button class="btn-icon" @click="refresh" title="Làm mới">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </div>
        <div class="tree-body">
            <div v-for="category in categories" :key="category.id" class="tree-node">
                <div class="node-header" @click="toggleNode(category.id)" :class="{ expanded: expandedNodes.includes(category.id) }">
                    <i class="node-icon" :class="expandedNodes.includes(category.id) ? 'fas fa-folder-open' : 'fas fa-folder'"></i>
                    <span class="node-name">{{ category.name }}</span>
                    <span class="node-badge">{{ category.criteria_count || 0 }} tiêu chí</span>
                    <i class="node-arrow fas fa-chevron-right" :class="{ rotated: expandedNodes.includes(category.id) }"></i>
                </div>
                <div v-show="expandedNodes.includes(category.id)" class="node-children">
                    <div v-for="child in category.children" :key="child.id" class="child-item" @click="selectCategory(child)">
                        <i class="fas fa-tag"></i>
                        <span class="child-name">{{ child.name }}</span>
                        <span class="child-code">{{ child.code }}</span>
                        <span class="child-badge">{{ child.criteria_count || 0 }}</span>
                    </div>
                    <div v-if="!category.children || category.children.length === 0" class="no-children">
                        <i class="fas fa-list-ul"></i>
                        <span>{{ category.criteria_count || 0 }} tiêu chí</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const emit = defineEmits(['select'])

const categories = ref([])
const expandedNodes = ref([])

const fetchCategories = async () => {
    const response = await fetch('/api/v1/categories/tree')
    const data = await response.json()
    categories.value = data.data || []
    expandedNodes.value = [1] // Mở rộng danh mục đầu tiên
}

const toggleNode = (id) => {
    const index = expandedNodes.value.indexOf(id)
    if (index > -1) expandedNodes.value.splice(index, 1)
    else expandedNodes.value.push(id)
}

const expandAll = () => {
    const allIds = []
    const collectIds = (items) => {
        items.forEach(item => {
            allIds.push(item.id)
            if (item.children) collectIds(item.children)
        })
    }
    collectIds(categories.value)
    expandedNodes.value = allIds
}

const collapseAll = () => { expandedNodes.value = [] }
const refresh = () => fetchCategories()
const selectCategory = (category) => emit('select', category)

onMounted(fetchCategories)
</script>

<style scoped>
.category-tree { background: var(--card-bg); border-radius: 12px; padding: 16px; box-shadow: var(--shadow-sm); }
.tree-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--border-color); }
.tree-header h3 { margin: 0; font-size: 16px; }
.tree-actions { display: flex; gap: 8px; }
.btn-icon { background: none; border: none; cursor: pointer; padding: 6px; border-radius: 6px; color: var(--text-secondary); transition: all 0.2s; }
.btn-icon:hover { background: var(--bg-secondary); color: var(--primary-600); }
.tree-node { margin-bottom: 4px; }
.node-header { display: flex; align-items: center; gap: 8px; padding: 10px 12px; cursor: pointer; border-radius: 8px; transition: background 0.2s; }
.node-header:hover { background: var(--bg-secondary); }
.node-icon { color: #f59e0b; font-size: 18px; }
.node-name { flex: 1; font-weight: 500; }
.node-badge { font-size: 11px; padding: 2px 8px; background: var(--bg-secondary); border-radius: 20px; color: var(--text-secondary); }
.node-arrow { font-size: 12px; color: var(--text-secondary); transition: transform 0.2s; }
.node-arrow.rotated { transform: rotate(90deg); }
.node-children { margin-left: 32px; padding-left: 12px; border-left: 1px dashed var(--border-color); }
.child-item { display: flex; align-items: center; gap: 8px; padding: 8px 12px; cursor: pointer; border-radius: 8px; transition: background 0.2s; }
.child-item:hover { background: var(--bg-secondary); }
.child-name { flex: 1; font-size: 14px; }
.child-code { font-family: monospace; font-size: 11px; color: var(--text-secondary); }
.child-badge { font-size: 11px; padding: 2px 6px; background: var(--primary-50); color: var(--primary-600); border-radius: 12px; }
.no-children { padding: 8px 12px; font-size: 12px; color: var(--text-secondary); display: flex; align-items: center; gap: 8px; }
</style>