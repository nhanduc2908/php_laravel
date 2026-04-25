/**
 * useTable - Composable for table pagination and sorting
 */

import { ref, reactive, computed } from 'vue'

export function useTable(fetchFunction, options = {}) {
    const {
        defaultPageSize = 15,
        pageSizes = [10, 15, 25, 50, 100],
        defaultSort = { key: 'id', order: 'desc' },
    } = options
    
    const data = ref([])
    const loading = ref(false)
    const currentPage = ref(1)
    const totalPages = ref(1)
    const totalItems = ref(0)
    const perPage = ref(defaultPageSize)
    const sortKey = ref(defaultSort.key)
    const sortOrder = ref(defaultSort.order)
    const search = ref('')
    const filters = reactive({})
    
    const pagination = computed(() => ({
        currentPage: currentPage.value,
        totalPages: totalPages.value,
        totalItems: totalItems.value,
        perPage: perPage.value,
    }))
    
    const sort = computed(() => ({
        key: sortKey.value,
        order: sortOrder.value,
    }))
    
    const fetchData = async () => {
        loading.value = true
        try {
            const params = {
                page: currentPage.value,
                per_page: perPage.value,
                sort: sortKey.value,
                order: sortOrder.value,
                search: search.value,
                ...filters,
            }
            const response = await fetchFunction(params)
            data.value = response.data || []
            totalItems.value = response.total || 0
            totalPages.value = response.last_page || 1
            return response
        } finally {
            loading.value = false
        }
    }
    
    const changePage = (page) => {
        currentPage.value = page
        fetchData()
    }
    
    const changePageSize = (size) => {
        perPage.value = size
        currentPage.value = 1
        fetchData()
    }
    
    const changeSort = (key, order) => {
        sortKey.value = key
        sortOrder.value = order
        fetchData()
    }
    
    const handleSort = ({ key, order }) => {
        changeSort(key, order)
    }
    
    const setSearch = (value) => {
        search.value = value
        currentPage.value = 1
        fetchData()
    }
    
    const setFilter = (key, value) => {
        filters[key] = value
        currentPage.value = 1
        fetchData()
    }
    
    const resetFilters = () => {
        Object.keys(filters).forEach(key => {
            delete filters[key]
        })
        search.value = ''
        currentPage.value = 1
        fetchData()
    }
    
    const refresh = () => {
        fetchData()
    }
    
    return {
        // State
        data,
        loading,
        currentPage,
        totalPages,
        totalItems,
        perPage,
        sortKey,
        sortOrder,
        search,
        filters,
        pagination,
        sort,
        // Methods
        fetchData,
        changePage,
        changePageSize,
        changeSort,
        handleSort,
        setSearch,
        setFilter,
        resetFilters,
        refresh,
    }
}