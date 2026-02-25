@extends('theme::layouts.admin')

@section('title', 'خطأ 500')

@section('content')
    <div class="admin-error-page">
        <!-- Decorative Background -->
        <div class="admin-error-bg-decoration admin-error-bg-1"></div>
        <div class="admin-error-bg-decoration admin-error-bg-2"></div>

        <div class="admin-error-wrapper">
            <!-- Icon -->
            <div class="admin-error-icon animate-in delay-1">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <!-- Error Code -->
            <div class="admin-error-code animate-in delay-1">500</div>

            <!-- Title -->
            <h1 class="admin-error-title animate-in delay-2">خطأ في الخادم</h1>

            <!-- Message -->
            <p class="admin-error-message animate-in delay-2">
                حدث خطأ غير متوقع في النظام. نعمل على حل المشكلة.
            </p>

            <!-- Buttons -->
            <div class="admin-error-buttons animate-in delay-3">
                <a href="{{ route('dashboard') }}" class="admin-error-btn admin-error-btn-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>العودة للوحة التحكم</span>
                </a>
                <a href="{{ url()->current() }}" class="admin-error-btn admin-error-btn-secondary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>إعادة المحاولة</span>
                </a>
            </div>

            <!-- Admin Quick Links -->
            <div class="admin-error-links animate-in delay-4">
                <p class="admin-error-links-title">روابط سريعة:</p>
                <div class="admin-error-links-list">
                    <a href="{{ route('admin.posts.index') }}">المقالات</a>
                    <a href="{{ route('admin.categories.index') }}">الأقسام</a>
                    <a href="{{ route('dashboard') }}">لوحة التحكم</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    @include('theme::errors.partials.error-styles')
@endpush