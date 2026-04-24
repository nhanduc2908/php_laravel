<template>
    <div class="alerts"><h2>Cảnh báo</h2>
        <div class="alert-list"><div v-for="a in alerts" :key="a.id" :class="['alert-item', a.severity, { unread: !a.is_read }]"><i :class="getIcon(a.type)"></i> {{ a.title }} - {{ a.message }}<button @click="markRead(a.id)"><i class="fas fa-check"></i></button></div></div>
    </div>
</template>