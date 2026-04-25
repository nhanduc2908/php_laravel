/**
 * SOCKET PLUGIN - WebSocket plugin for Vue
 */

import { io } from 'socket.io-client'

// Socket instance
let socket = null
let isConnected = false

// Event handlers storage
const eventHandlers = new Map()
const globalHandlers = {
    connect: [],
    disconnect: [],
    connect_error: [],
    reconnect: [],
    reconnect_attempt: [],
    reconnect_error: [],
    reconnect_failed: [],
}

// Configuration
const config = {
    url: import.meta.env.VITE_WS_URL || 'ws://localhost:3000',
    path: '/socket.io',
    options: {
        transports: ['websocket', 'polling'],
        autoConnect: false,
        reconnection: true,
        reconnectionAttempts: 5,
        reconnectionDelay: 1000,
        reconnectionDelayMax: 5000,
        timeout: 20000,
    },
}

// Trigger global handlers
const triggerGlobal = (event, data) => {
    if (globalHandlers[event]) {
        globalHandlers[event].forEach(handler => handler(data))
    }
}

// Initialize socket connection
export const initSocket = (token = null) => {
    if (socket) return socket
    
    const options = { ...config.options }
    if (token) {
        options.auth = { token }
    }
    
    socket = io(config.url, options)
    
    // Setup event listeners
    socket.on('connect', () => {
        isConnected = true
        triggerGlobal('connect', { socket })
        console.log('Socket connected')
    })
    
    socket.on('disconnect', (reason) => {
        isConnected = false
        triggerGlobal('disconnect', { reason })
        console.log('Socket disconnected:', reason)
    })
    
    socket.on('connect_error', (error) => {
        triggerGlobal('connect_error', { error })
        console.error('Socket connection error:', error)
    })
    
    socket.on('reconnect', (attemptNumber) => {
        triggerGlobal('reconnect', { attemptNumber })
        console.log('Socket reconnected after', attemptNumber, 'attempts')
    })
    
    socket.on('reconnect_attempt', (attemptNumber) => {
        triggerGlobal('reconnect_attempt', { attemptNumber })
    })
    
    socket.on('reconnect_error', (error) => {
        triggerGlobal('reconnect_error', { error })
    })
    
    socket.on('reconnect_failed', () => {
        triggerGlobal('reconnect_failed', {})
        console.error('Socket reconnection failed')
    })
    
    // Register all stored event handlers
    for (const [event, handlers] of eventHandlers) {
        for (const handler of handlers) {
            socket.on(event, handler)
        }
    }
    
    return socket
}

// Connect socket
export const connectSocket = (token = null) => {
    const sock = initSocket(token)
    if (!socket.connected) {
        socket.connect()
    }
    return sock
}

// Disconnect socket
export const disconnectSocket = () => {
    if (socket && socket.connected) {
        socket.disconnect()
    }
}

// Get socket instance
export const getSocket = () => socket

// Check connection status
export const isSocketConnected = () => isConnected

// Register event handler
export const onSocketEvent = (event, callback) => {
    if (!eventHandlers.has(event)) {
        eventHandlers.set(event, [])
    }
    eventHandlers.get(event).push(callback)
    
    if (socket) {
        socket.on(event, callback)
    }
}

// Remove event handler
export const offSocketEvent = (event, callback) => {
    if (eventHandlers.has(event)) {
        const handlers = eventHandlers.get(event)
        const index = handlers.indexOf(callback)
        if (index !== -1) {
            handlers.splice(index, 1)
        }
        if (handlers.length === 0) {
            eventHandlers.delete(event)
        }
    }
    
    if (socket) {
        socket.off(event, callback)
    }
}

// Emit event
export const emitSocketEvent = (event, data) => {
    if (socket && socket.connected) {
        socket.emit(event, data)
        return true
    }
    console.warn('Socket not connected, cannot emit:', event)
    return false
}

// Register global callback
export const onSocketConnect = (callback) => {
    globalHandlers.connect.push(callback)
    if (isConnected) callback({ socket })
}

export const onSocketDisconnect = (callback) => {
    globalHandlers.disconnect.push(callback)
}

export const onSocketError = (callback) => {
    globalHandlers.connect_error.push(callback)
}

export const onSocketReconnect = (callback) => {
    globalHandlers.reconnect.push(callback)
}

// Room management
export const joinRoom = (room) => {
    emitSocketEvent('join', { room })
}

export const leaveRoom = (room) => {
    emitSocketEvent('leave', { room })
}

// Subscribe to specific channels
export const subscribeToServer = (serverId) => {
    joinRoom(`server:${serverId}`)
}

export const subscribeToAdmin = () => {
    joinRoom('admin')
}

export const subscribeToUser = (userId) => {
    joinRoom(`user:${userId || 'me'}`)
}

export const subscribeToFile = (fileId) => {
    joinRoom(`file:${fileId}`)
}

// Unsubscribe from channels
export const unsubscribeFromServer = (serverId) => {
    leaveRoom(`server:${serverId}`)
}

export const unsubscribeFromAdmin = () => {
    leaveRoom('admin')
}

export const unsubscribeFromUser = () => {
    leaveRoom('user')
}

export const unsubscribeFromFile = (fileId) => {
    leaveRoom(`file:${fileId}`)
}

// Socket event types
export const SOCKET_EVENTS = {
    // Connection events
    CONNECT: 'connect',
    DISCONNECT: 'disconnect',
    CONNECT_ERROR: 'connect_error',
    RECONNECT: 'reconnect',
    RECONNECT_ATTEMPT: 'reconnect_attempt',
    RECONNECT_ERROR: 'reconnect_error',
    RECONNECT_FAILED: 'reconnect_failed',
    
    // Room events
    JOIN: 'join',
    LEAVE: 'leave',
    
    // Application events
    ALERT: 'alert',
    VULNERABILITY: 'vulnerability',
    SCAN_PROGRESS: 'scan_progress',
    ASSESSMENT_COMPLETE: 'assessment_complete',
    FILE_UPDATE: 'file_update',
    NOTIFICATION: 'notification',
    
    // Auth events
    AUTHENTICATE: 'authenticate',
    UNAUTHORIZED: 'unauthorized',
}

// Create Vue plugin
export default {
    install(app, options = {}) {
        // Initialize socket
        const token = options.token || null
        
        app.config.globalProperties.$socket = {
            connect: () => connectSocket(token),
            disconnect: disconnectSocket,
            on: onSocketEvent,
            off: offSocketEvent,
            emit: emitSocketEvent,
            isConnected: isSocketConnected,
            joinRoom,
            leaveRoom,
            subscribeToServer,
            subscribeToAdmin,
            subscribeToUser,
            subscribeToFile,
            unsubscribeFromServer,
            unsubscribeFromAdmin,
            unsubscribeFromUser,
            unsubscribeFromFile,
        }
        
        // Provide for Composition API
        app.provide('socket', {
            connect: () => connectSocket(token),
            disconnect: disconnectSocket,
            on: onSocketEvent,
            off: offSocketEvent,
            emit: emitSocketEvent,
            isConnected: isSocketConnected,
            joinRoom,
            leaveRoom,
        })
        
        // Auto connect if specified
        if (options.autoConnect) {
            connectSocket(token)
        }
        
        // Auto disconnect on app unmount
        if (typeof window !== 'undefined') {
            window.addEventListener('beforeunload', () => {
                disconnectSocket()
            })
        }
    },
}

// Hook for Composition API
export function useSocket() {
    return {
        connect: connectSocket,
        disconnect: disconnectSocket,
        on: onSocketEvent,
        off: offSocketEvent,
        emit: emitSocketEvent,
        isConnected: isSocketConnected,
        joinRoom,
        leaveRoom,
        subscribeToServer,
        subscribeToAdmin,
        subscribeToUser,
        subscribeToFile,
        unsubscribeFromServer,
        unsubscribeFromAdmin,
        unsubscribeFromUser,
        unsubscribeFromFile,
        events: SOCKET_EVENTS,
    }
}