<style>
    /* Admin Error Page — Mirrors public error design */
    .admin-error-page {
        position: relative;
        min-height: 60vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 1.5rem;
        overflow: hidden;
    }

    /* Decorative Background */
    .admin-error-bg-decoration {
        position: absolute;
        border-radius: 50%;
        opacity: 0.06;
        pointer-events: none;
    }

    .admin-error-bg-1 {
        width: 400px;
        height: 400px;
        background: #3B75B0;
        top: -120px;
        right: -120px;
    }

    .admin-error-bg-2 {
        width: 280px;
        height: 280px;
        background: #1F3A6E;
        bottom: -80px;
        left: -80px;
    }

    /* Content Wrapper */
    .admin-error-wrapper {
        max-width: 500px;
        width: 100%;
        text-align: center;
        position: relative;
        z-index: 10;
    }

    /* Icon */
    .admin-error-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #3B75B0 0%, #6FA8DC 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: 0 10px 40px rgba(195, 124, 84, 0.25);
        animation: adminErrorPulse 2s ease-in-out infinite;
    }

    @keyframes adminErrorPulse {

        0%,
        100% {
            box-shadow: 0 10px 40px rgba(195, 124, 84, 0.25);
        }

        50% {
            box-shadow: 0 15px 50px rgba(195, 124, 84, 0.35);
        }
    }

    .admin-error-icon svg {
        width: 32px;
        height: 32px;
        color: white;
    }

    /* Error Code */
    .admin-error-code {
        font-size: clamp(80px, 15vw, 150px);
        font-weight: 900;
        line-height: 1;
        background: linear-gradient(135deg, #1F3A6E 0%, #3B75B0 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
        animation: adminErrorFloat 3s ease-in-out infinite;
    }

    @keyframes adminErrorFloat {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-8px);
        }
    }

    /* Title */
    .admin-error-title {
        font-size: clamp(1.25rem, 4vw, 1.75rem);
        font-weight: 700;
        color: #1F3A6E;
        margin-bottom: 1rem;
    }

    /* Message */
    .admin-error-message {
        font-size: clamp(0.9rem, 2.5vw, 1rem);
        color: #6f6f6f;
        line-height: 1.9;
        margin-bottom: 2rem;
        padding: 0 0.5rem;
    }

    /* Buttons */
    .admin-error-buttons {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        align-items: center;
        justify-content: center;
        width: 100%;
        max-width: 320px;
        margin: 0 auto;
    }

    .admin-error-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.875rem 1.5rem;
        border-radius: 50px;
        font-size: 0.95rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        cursor: pointer;
        border: none;
        width: 100%;
    }

    .admin-error-btn svg {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    .admin-error-btn-primary {
        background: linear-gradient(135deg, #1F3A6E 0%, #333 100%);
        color: white;
        box-shadow: 0 4px 20px rgba(31, 31, 31, 0.25);
    }

    .admin-error-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(31, 31, 31, 0.35);
        color: white;
        text-decoration: none;
    }

    .admin-error-btn-secondary {
        background: white;
        color: #1F3A6E;
        border: 2px solid rgba(0, 0, 0, 0.08);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .admin-error-btn-secondary:hover {
        border-color: #3B75B0;
        color: #3B75B0;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    /* Quick Links */
    .admin-error-links {
        margin-top: 2.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(0, 0, 0, 0.06);
        width: 100%;
    }

    .admin-error-links-title {
        font-size: 0.85rem;
        color: #6f6f6f;
        margin-bottom: 0.75rem;
    }

    .admin-error-links-list {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .admin-error-links-list a {
        color: #1F3A6E;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: color 0.2s;
        padding: 0.25rem 0;
    }

    .admin-error-links-list a:hover {
        color: #3B75B0;
    }

    /* Desktop */
    @media (min-width: 640px) {
        .admin-error-icon {
            width: 80px;
            height: 80px;
            margin-bottom: 2rem;
        }

        .admin-error-icon svg {
            width: 40px;
            height: 40px;
        }

        .admin-error-message {
            padding: 0;
        }

        .admin-error-buttons {
            flex-direction: row;
            justify-content: center;
            max-width: none;
            width: auto;
        }

        .admin-error-btn {
            width: auto;
            padding: 0.875rem 2rem;
        }
    }

    @media (min-width: 768px) {
        .admin-error-wrapper {
            max-width: 550px;
        }

        .admin-error-links-list {
            gap: 1.5rem;
        }
    }

    /* Animations */
    @keyframes adminSlideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .admin-error-page .animate-in {
        animation: adminSlideUp 0.5s ease-out forwards;
    }

    .admin-error-page .delay-1 {
        animation-delay: 0.05s;
        opacity: 0;
    }

    .admin-error-page .delay-2 {
        animation-delay: 0.15s;
        opacity: 0;
    }

    .admin-error-page .delay-3 {
        animation-delay: 0.25s;
        opacity: 0;
    }

    .admin-error-page .delay-4 {
        animation-delay: 0.35s;
        opacity: 0;
    }
</style>