<template>
    <div class="files-management"><div class="header-actions"><h2>Tệp đánh giá</h2><button class="btn-primary" @click="openCreateModal"><i class="fas fa-plus"></i> Tạo tệp</button></div>
        <div class="files-list"><div v-for="f in files" :key="f.id" class="file-item"><i class="fas fa-file"></i> {{ f.title }} - {{ f.server_name }} - v{{ f.version }}<button @click="viewFile(f.id)"><i class="fas fa-eye"></i></button><button @click="editFile(f.id)"><i class="fas fa-edit"></i></button><button @click="shareFile(f.id)"><i class="fas fa-share"></i></button></div></div>
    </div>
</template>