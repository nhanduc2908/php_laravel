/**
 * CONSTANTS - Global constants for the application
 */

// API endpoints
export const API = {
    BASE_URL: import.meta.env.VITE_API_URL || 'http://localhost:8000',
    PREFIX: '/api/v1',
    TIMEOUT: 30000,
}

// WebSocket
export const WS = {
    URL: import.meta.env.VITE_WS_URL || 'ws://localhost:3000',
    PATH: '/socket.io',
    RECONNECTION_ATTEMPTS: 5,
    RECONNECTION_DELAY: 1000,
}

// Pagination
export const PAGINATION = {
    DEFAULT_PAGE_SIZE: 15,
    PAGE_SIZES: [10, 15, 25, 50, 100],
}

// Date formats
export const DATE_FORMATS = {
    DATE: 'DD/MM/YYYY',
    TIME: 'HH:mm:ss',
    DATETIME: 'DD/MM/YYYY HH:mm:ss',
    API_DATE: 'YYYY-MM-DD',
    API_DATETIME: 'YYYY-MM-DD HH:mm:ss',
}

// File upload
export const UPLOAD = {
    MAX_SIZE: 10 * 1024 * 1024, // 10MB
    ALLOWED_EXTENSIONS: ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip'],
    ALLOWED_MIME_TYPES: [
        'image/jpeg', 'image/png', 'image/gif',
        'application/pdf', 'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/zip',
    ],
}

// Severity levels
export const SEVERITY = {
    CRITICAL: 'critical',
    HIGH: 'high',
    MEDIUM: 'medium',
    LOW: 'low',
    INFO: 'info',
}

export const SEVERITY_LABELS = {
    [SEVERITY.CRITICAL]: 'Nghiêm trọng',
    [SEVERITY.HIGH]: 'Cao',
    [SEVERITY.MEDIUM]: 'Trung bình',
    [SEVERITY.LOW]: 'Thấp',
    [SEVERITY.INFO]: 'Thông tin',
}

export const SEVERITY_COLORS = {
    [SEVERITY.CRITICAL]: '#ef4444',
    [SEVERITY.HIGH]: '#f97316',
    [SEVERITY.MEDIUM]: '#eab308',
    [SEVERITY.LOW]: '#22c55e',
    [SEVERITY.INFO]: '#3b82f6',
}

// Status
export const STATUS = {
    ACTIVE: 'active',
    INACTIVE: 'inactive',
    PENDING: 'pending',
    COMPLETED: 'completed',
    FAILED: 'failed',
    DRAFT: 'draft',
    PUBLISHED: 'published',
    ARCHIVED: 'archived',
    OPEN: 'open',
    FIXED: 'fixed',
    FALSE_POSITIVE: 'false_positive',
}

export const STATUS_LABELS = {
    [STATUS.ACTIVE]: 'Hoạt động',
    [STATUS.INACTIVE]: 'Không hoạt động',
    [STATUS.PENDING]: 'Chờ xử lý',
    [STATUS.COMPLETED]: 'Hoàn tất',
    [STATUS.FAILED]: 'Thất bại',
    [STATUS.DRAFT]: 'Nháp',
    [STATUS.PUBLISHED]: 'Đã xuất bản',
    [STATUS.ARCHIVED]: 'Lưu trữ',
    [STATUS.OPEN]: 'Chưa xử lý',
    [STATUS.FIXED]: 'Đã sửa',
    [STATUS.FALSE_POSITIVE]: 'False Positive',
}

// Roles
export const ROLES = {
    SUPER_ADMIN: 'super_admin',
    ADMIN: 'admin',
    SECURITY_OFFICER: 'security_officer',
    VIEWER: 'viewer',
    AUDITOR: 'auditor',
}

export const ROLE_LABELS = {
    [ROLES.SUPER_ADMIN]: 'Super Admin',
    [ROLES.ADMIN]: 'Admin',
    [ROLES.SECURITY_OFFICER]: 'Security Officer',
    [ROLES.VIEWER]: 'Viewer',
    [ROLES.AUDITOR]: 'Auditor',
}

// Assessment types
export const ASSESSMENT_TYPES = {
    FULL: 'full',
    SUMMARY: 'summary',
    COMPLIANCE: 'compliance',
    VULNERABILITY: 'vulnerability',
}

// Report formats
export const REPORT_FORMATS = {
    PDF: 'pdf',
    EXCEL: 'excel',
    CSV: 'csv',
}

// File types
export const FILE_TYPES = {
    DOCUMENT: 'document',
    SPREADSHEET: 'spreadsheet',
    PDF: 'pdf',
    IMAGE: 'image',
    ARCHIVE: 'archive',
}

// Local storage keys
export const STORAGE_KEYS = {
    ACCESS_TOKEN: 'access_token',
    REFRESH_TOKEN: 'refresh_token',
    USER: 'user',
    THEME: 'theme',
    SIDEBAR_COLLAPSED: 'sidebar_collapsed',
    SIDEBAR_HIDDEN: 'sidebar_hidden',
    LOCALE: 'locale',
}

// Event names
export const EVENTS = {
    // WebSocket events
    WS_CONNECT: 'connect',
    WS_DISCONNECT: 'disconnect',
    WS_ALERT_NEW: 'alert.new',
    WS_VULNERABILITY_NEW: 'vulnerability.new',
    WS_SCAN_PROGRESS: 'scan.progress',
    WS_ASSESSMENT_COMPLETED: 'assessment.completed',
    WS_FILE_SHARED: 'file.shared',
    WS_NOTIFICATION: 'notification',
    
    // Custom events
    LANGUAGE_CHANGED: 'language-changed',
    THEME_CHANGED: 'theme-changed',
    GLOBAL_SEARCH: 'global-search',
}

// Error messages
export const ERROR_MESSAGES = {
    NETWORK_ERROR: 'Lỗi kết nối mạng. Vui lòng kiểm tra kết nối.',
    SERVER_ERROR: 'Lỗi máy chủ. Vui lòng thử lại sau.',
    UNAUTHORIZED: 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.',
    FORBIDDEN: 'Bạn không có quyền truy cập.',
    NOT_FOUND: 'Không tìm thấy dữ liệu.',
    VALIDATION_ERROR: 'Dữ liệu không hợp lệ. Vui lòng kiểm tra lại.',
    FILE_TOO_LARGE: 'Kích thước file vượt quá giới hạn (10MB).',
    FILE_TYPE_NOT_ALLOWED: 'Loại file không được phép.',
}