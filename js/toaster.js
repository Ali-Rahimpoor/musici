/**
 * Toaster - کتابخانه حرفه‌ای نوتیفیکیشن
 * @version 1.0.0
 * @author AliRahimpoor
 * @license MIT
 */

class Toaster {
    #config;
    #container;
    #toasts = [];
    #nextId = 0;

    constructor(config = {}) {
        this.#config = {
            position: config.position || 'top-right',
            duration: config.duration || 5000,
            animation: config.animation || 'slide',
            maxToasts: config.maxToasts || 5,
            rtl: config.rtl || false,
            pauseOnHover: config.pauseOnHover !== false,
            closeButton: config.closeButton !== false,
            progressBar: config.progressBar !== false,
            ...config
        };

        this.#init();
    }

    #init() {
        this.#createContainer();
        this.#injectStyles();
    }

    #createContainer() {
        if (this.#container) return;

        this.#container = document.createElement('div');
        this.#container.className = `toaster-container toaster-${this.#config.position}`;
        
        const positions = {
            'top-right': { top: '20px', right: '20px', left: 'auto', bottom: 'auto' },
            'top-left': { top: '20px', left: '20px', right: 'auto', bottom: 'auto' },
            'top-center': { top: '20px', left: '50%', right: 'auto', bottom: 'auto', transform: 'translateX(-50%)' },
            'bottom-right': { bottom: '20px', right: '20px', top: 'auto', left: 'auto' },
            'bottom-left': { bottom: '20px', left: '20px', top: 'auto', right: 'auto' },
            'bottom-center': { bottom: '20px', left: '50%', right: 'auto', top: 'auto', transform: 'translateX(-50%)' }
        };

        const pos = positions[this.#config.position] || positions['top-right'];
        Object.assign(this.#container.style, {
            position: 'fixed',
            zIndex: '10000',
            display: 'flex',
            flexDirection: 'column',
            gap: '12px',
            pointerEvents: 'none',
            ...pos
        });

        document.body.appendChild(this.#container);
    }

    #injectStyles() {
        if (document.getElementById('toaster-styles')) return;

        const style = document.createElement('style');
        style.id = 'toaster-styles';
        style.textContent = `
            @font-face {
            font-family: 'musicjooyar';
            font-style: normal;
            font-weight: 400;
            src: url("../fonts/musicjooyar.eot?377f8e241995b354f6ffc28a93709e9b?#iefix") format("embedded-opentype"),
            url("../fonts/musicjooyar.woff2?377f8e241995b354f6ffc28a93709e9b") format("woff2"),
            url("../fonts/musicjooyar.woff?377f8e241995b354f6ffc28a93709e9b") format("woff");
            }
            .toaster-container {
                font-family: musicjooyar, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
                direction: ${this.#config.rtl ? 'rtl' : 'ltr'};
            }

            .toast {
                pointer-events: auto;
                min-width: 300px;
                max-width: 450px;
                background: white;
                border-radius: 12px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12), 0 4px 12px rgba(0, 0, 0, 0.06);
                position: relative;
                overflow: hidden;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .toast-slide {
                animation: toastSlide 0.3s ease-out;
            }

            .toast-fade {
                animation: toastFade 0.2s ease-out;
            }

            .toast-bounce {
                animation: toastBounce 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            }

            @keyframes toastSlide {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            @keyframes toastFade {
                from {
                    opacity: 0;
                }
                to {
                    opacity: 1;
                }
            }

            @keyframes toastBounce {
                0% {
                    transform: scale(0.3);
                    opacity: 0;
                }
                50% {
                    transform: scale(1.05);
                }
                100% {
                    transform: scale(1);
                    opacity: 1;
                }
            }

            .toast-removing {
                animation: toastRemove 0.3s ease-out forwards !important;
            }

            @keyframes toastRemove {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }

            .toast-progress {
                position: absolute;
                bottom: 0;
                left: 0;
                height: 3px;
                width: 100%;
                background: rgba(0, 0, 0, 0.1);
            }

            .toast-progress-bar {
                height: 100%;
                width: 100%;
                background: currentColor;
                animation: progress linear forwards;
            }

            @keyframes progress {
                from {
                    width: 100%;
                }
                to {
                    width: 0%;
                }
            }

            .toast:hover .toast-progress-bar {
                animation-play-state: paused !important;
            }

            .toast-content {
                display: flex;
                align-items: flex-start;
                gap: 12px;
                padding: 16px;
            }

            .toast-icon {
                    width: 24px;
                    height: 24px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-shrink: 0;
                    font-weight: bold;
                    font-size: 14px;
                }

                .toast-text {
                    flex: 1;
                }

                .toast-title {
                    margin: 0 0 4px 0;
                    font-size: 14px;
                    font-weight: 600;
                    line-height: 1.4;
                }

                .toast-message {
                    margin: 0;
                    font-size: 13px;
                    opacity: 0.8;
                    line-height: 1.5;
                }

                .toast-close {
                    background: none;
                    border: none;
                    cursor: pointer;
                    padding: 4px;
                    opacity: 0.5;
                    transition: opacity 0.2s;
                    font-size: 20px;
                    line-height: 1;
                    flex-shrink: 0;
                }

                .toast-close:hover {
                    opacity: 1;
                }

                /* Types */
                .toast-success {
                    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
                    border-left: 4px solid #22c55e;
                }
                .toast-success .toast-icon {
                    background: #22c55e;
                    color: white;
                }
                .toast-success .toast-title {
                    color: #166534;
                }

                .toast-error {
                    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
                    border-left: 4px solid #ef4444;
                }
                .toast-error .toast-icon {
                    background: #ef4444;
                    color: white;
                }
                .toast-error .toast-title {
                    color: #991b1b;
                }

                .toast-info {
                    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
                    border-left: 4px solid #3b82f6;
                }
                .toast-info .toast-icon {
                    background: #3b82f6;
                    color: white;
                }
                .toast-info .toast-title {
                    color: #1e3a8a;
                }

                .toast-warning {
                    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
                    border-left: 4px solid #f59e0b;
                }
                .toast-warning .toast-icon {
                    background: #f59e0b;
                    color: white;
                }
                .toast-warning .toast-title {
                    color: #92400e;
                }

                /* RTL Support */
                [dir="rtl"] .toast {
                    border-left: none;
                    border-right: 4px solid;
                }

                [dir="rtl"] .toast-success {
                    border-right-color: #22c55e;
                }
                [dir="rtl"] .toast-error {
                    border-right-color: #ef4444;
                }
                [dir="rtl"] .toast-info {
                    border-right-color: #3b82f6;
                }
                [dir="rtl"] .toast-warning {
                    border-right-color: #f59e0b;
                }
        `;
        document.head.appendChild(style);
    }

    #getIcon(type) {
        const icons = {
            success: '✓',
            error: '✗',
            info: 'ℹ',
            warning: '⚠'
        };
        return icons[type] || '•';
    }

    #createToast(title, message, type, options = {}) {
        const id = this.#nextId++;
        const toast = document.createElement('div');
        toast.className = `toast toast-${type} toast-${this.#config.animation}`;
        toast.setAttribute('data-id', id);

        const duration = options.duration || this.#config.duration;
        const icon = this.#getIcon(type);

        toast.innerHTML = `
            <div class="toast-content">
                <div class="toast-icon">${icon}</div>
                <div class="toast-text">
                    <h4 class="toast-title">${this.#escapeHtml(title)}</h4>
                    <p class="toast-message">${this.#escapeHtml(message)}</p>
                </div>
                ${this.#config.closeButton ? '<button class="toast-close">×</button>' : ''}
            </div>
            ${this.#config.progressBar && duration ? `
                <div class="toast-progress">
                    <div class="toast-progress-bar" style="animation-duration: ${duration}ms;"></div>
                </div>
            ` : ''}
        `;

        // Close button event
        if (this.#config.closeButton) {
            const closeBtn = toast.querySelector('.toast-close');
            closeBtn.addEventListener('click', () => this.#removeToast(id));
        }

        return { id, toast, duration };
    }

    #escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    #addToast(toastData) {
        const { id, toast, duration } = toastData;
        
        // Remove oldest if max exceeded
        if (this.#toasts.length >= this.#config.maxToasts) {
            const oldest = this.#toasts.shift();
            this.#removeToast(oldest.id);
        }

        this.#container.appendChild(toast);
        
        let timeout;
        if (duration) {
            timeout = setTimeout(() => this.#removeToast(id), duration);
        }

        this.#toasts.push({ id, toast, timeout });

        // Pause on hover
        if (this.#config.pauseOnHover && duration) {
            toast.addEventListener('mouseenter', () => {
                if (timeout) clearTimeout(timeout);
                const progressBar = toast.querySelector('.toast-progress-bar');
                if (progressBar) {
                    const computedStyle = window.getComputedStyle(progressBar);
                    const transform = computedStyle.getPropertyValue('animation');
                    progressBar.style.animationPlayState = 'paused';
                }
            });

            toast.addEventListener('mouseleave', () => {
                const newTimeout = setTimeout(() => this.#removeToast(id), 2000);
                const toastObj = this.#toasts.find(t => t.id === id);
                if (toastObj) toastObj.timeout = newTimeout;
                
                const progressBar = toast.querySelector('.toast-progress-bar');
                if (progressBar) {
                    progressBar.style.animationPlayState = 'running';
                }
            });
        }

        return id;
    }

    #removeToast(id) {
        const index = this.#toasts.findIndex(t => t.id === id);
        if (index === -1) return;

        const { toast, timeout } = this.#toasts[index];
        
        if (timeout) clearTimeout(timeout);
        
        toast.classList.add('toast-removing');
        toast.addEventListener('animationend', () => {
            if (toast.parentNode) toast.parentNode.removeChild(toast);
        });
        
        this.#toasts.splice(index, 1);
    }

    // Public API
    show(title, message, type = 'info', options = {}) {
        const toastData = this.#createToast(title, message, type, options);
        return this.#addToast(toastData);
    }

    success(title, message, options = {}) {
        return this.show(title, message, 'success', options);
    }

    error(title, message, options = {}) {
        return this.show(title, message, 'error', options);
    }

    info(title, message, options = {}) {
        return this.show(title, message, 'info', options);
    }

    warning(title, message, options = {}) {
        return this.show(title, message, 'warning', options);
    }

    remove(id) {
        this.#removeToast(id);
    }

    removeAll() {
        this.#toasts.forEach(toast => this.#removeToast(toast.id));
    }

    updateConfig(config) {
        Object.assign(this.#config, config);
        if (config.position) {
            this.#container.remove();
            this.#container = null;
            this.#createContainer();
        }
    }
}

// Export for different environments
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { Toaster };
}

window.Toaster = Toaster;