@extends('theme::layouts.admin')

@section('title', 'ط¥ظ†ط´ط§ط، ظ…ط³طھط®ط¯ظ… ط¬ط¯ظٹط¯')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-3xl font-serif font-bold text-brand-primary">ط¥ظ†ط´ط§ط، ظ…ط³طھط®ط¯ظ… ط¬ط¯ظٹط¯</h1>
    <x-feature-link feature="manage_admins" route="admin.users.index" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors bg-white">
        ط§ظ„ط¹ظˆط¯ط© ظ„ظ„ظ‚ط§ط¦ظ…ط©
    </x-feature-link>
</div>

<form action="{{ route('admin.users.store') }}" method="POST" id="user-form">
    @csrf
    
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Main Column (Content) -->
        <div class="w-full lg:w-2/3 space-y-6">
            
            <!-- Basic Information -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">ط§ظ„ظ…ط¹ظ„ظˆظ…ط§طھ ط§ظ„ط£ط³ط§ط³ظٹط©</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">ط§ظ„ط§ط³ظ… ط§ظ„ظƒط§ظ…ظ„</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent @error('name') border-red-500 @enderror"
                               placeholder="ط£ط¯ط®ظ„ ط§ظ„ط§ط³ظ… ط§ظ„ظƒط§ظ…ظ„">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">ط§ظ„ط¨ط±ظٹط¯ ط§ظ„ط¥ظ„ظƒطھط±ظˆظ†ظٹ</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent @error('email') border-red-500 @enderror"
                               placeholder="example@domain.com"
                               dir="ltr">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Password Section -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">ظƒظ„ظ…ط© ط§ظ„ظ…ط±ظˆط±</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">ظƒظ„ظ…ط© ط§ظ„ظ…ط±ظˆط±</label>
                        <input type="password" name="password" id="password" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent @error('password') border-red-500 @enderror"
                               placeholder="â€¢â€¢â€¢â€¢â€¢â€¢â€¢â€¢">
                        <p class="mt-1 text-xs text-gray-500">ط§طھط±ظƒظ‡ط§ ظپط§ط±ط؛ط© ظ„طھظˆظ„ظٹط¯ ظƒظ„ظ…ط© ظ…ط±ظˆط± ط¹ط´ظˆط§ط¦ظٹط© ط¢ظ…ظ†ط© (12 ط­ط±ظپ)</p>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">طھط£ظƒظٹط¯ ظƒظ„ظ…ط© ط§ظ„ظ…ط±ظˆط±</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent"
                               placeholder="â€¢â€¢â€¢â€¢â€¢â€¢â€¢â€¢">
                    </div>
                </div>
            </div>

        </div>

        <!-- Sidebar Column (Settings) -->
        <div class="w-full lg:w-1/3 space-y-6">
            
            <!-- Role & Permissions -->
            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">ط§ظ„طµظ„ط§ط­ظٹط§طھ</h3>
                
                <div class="space-y-4">
                    @if(auth()->user()->isSuperAdmin())
                    <label class="flex items-start cursor-pointer hover:bg-gray-50 p-3 rounded transition-colors">
                        <input type="checkbox" name="is_super_admin" value="1" {{ old('is_super_admin') ? 'checked' : '' }} 
                               class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 h-5 w-5 mt-0.5">
                        <div class="mr-3">
                            <span class="block text-sm font-medium text-gray-700">ظ…ط¯ظٹط± ط§ظ„ظ†ط¸ط§ظ…</span>
                            <span class="block text-xs text-gray-500">طµظ„ط§ط­ظٹط§طھ ظƒط§ظ…ظ„ط© ط¨ظ…ط§ ظپظٹ ط°ظ„ظƒ ط¥ط¯ط§ط±ط© ط§ظ„ظ…ط³طھط®ط¯ظ…ظٹظ†</span>
                        </div>
                    </label>
                    @endif
                    
                    <label class="flex items-start cursor-pointer hover:bg-gray-50 p-3 rounded transition-colors">
                        <input type="checkbox" name="is_admin" value="1" {{ old('is_admin', true) ? 'checked' : '' }} 
                               class="rounded border-gray-300 text-brand-accent focus:ring-brand-accent h-5 w-5 mt-0.5">
                        <div class="mr-3">
                            <span class="block text-sm font-medium text-gray-700">ظ…ط´ط±ظپ</span>
                            <span class="block text-xs text-gray-500">ظٹظ…ظƒظ†ظ‡ ط¥ط¯ط§ط±ط© ط§ظ„ظ…ط­طھظˆظ‰ (ط§ظ„ظ…ظ‚ط§ظ„ط§طھطŒ ط§ظ„ط£ظ‚ط³ط§ظ…طŒ ط§ظ„ظˆط³ظˆظ…)</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Publishing Actions -->
            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">ط§ظ„ط¥ط¬ط±ط§ط،ط§طھ</h3>

                <div class="flex items-center justify-between pt-4 border-t mt-4">
                    <x-feature-link feature="manage_admins" route="admin.users.index" class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors text-sm font-medium">
                        ط¥ظ„ط؛ط§ط،
                    </x-feature-link>
                    <button type="submit" class="px-6 py-2 bg-brand-primary text-white rounded hover:bg-opacity-90 transition-colors text-sm font-medium shadow-sm">
                        ط¥ظ†ط´ط§ط، ط§ظ„ظ…ط³طھط®ط¯ظ…
                    </button>
                </div>
            </div>

            <!-- Info Card -->
            <div class="bg-amber-50 border border-amber-200 p-4 rounded shadow-sm">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-amber-600 ml-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-sm text-amber-800">
                        <p class="font-semibold mb-1">ظ…ظ„ط§ط­ط¸ط© ظ‡ط§ظ…ط©</p>
                        <p class="text-xs">ط¥ط°ط§ طھط±ظƒطھ ظƒظ„ظ…ط© ط§ظ„ظ…ط±ظˆط± ظپط§ط±ط؛ط©طŒ ط³ظٹطھظ… ط¥ظ†ط´ط§ط، ظƒظ„ظ…ط© ظ…ط±ظˆط± ط¹ط´ظˆط§ط¦ظٹط© ط¢ظ…ظ†ط© (12 ط­ط±ظپ).</p>
                    </div>
                </div>
            </div>

            <!-- User Roles Info -->
            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-bold text-gray-800 mb-3 text-sm">ط£ظ†ظˆط§ط¹ ط§ظ„ظ…ط³طھط®ط¯ظ…ظٹظ†</h3>
                <div class="space-y-3">
                    @if(auth()->user()->isSuperAdmin())
                    <div class="flex items-start">
                        <div class="w-2 h-2 rounded-full bg-purple-600 mt-1.5 ml-2 flex-shrink-0"></div>
                        <div>
                            <p class="text-xs font-medium text-gray-700">ظ…ط¯ظٹط± ط§ظ„ظ†ط¸ط§ظ…</p>
                            <p class="text-xs text-gray-500">طµظ„ط§ط­ظٹط§طھ ظƒط§ظ…ظ„ط© ط¨ظ…ط§ ظپظٹ ط°ظ„ظƒ ط¥ط¯ط§ط±ط© ط§ظ„ظ…ط³طھط®ط¯ظ…ظٹظ†</p>
                        </div>
                    </div>
                    @endif
                    <div class="flex items-start">
                        <div class="w-2 h-2 rounded-full bg-brand-accent mt-1.5 ml-2 flex-shrink-0"></div>
                        <div>
                            <p class="text-xs font-medium text-gray-700">ظ…ط´ط±ظپ</p>
                            <p class="text-xs text-gray-500">ظٹظ…ظƒظ†ظ‡ ط¥ط¯ط§ط±ط© ط§ظ„ظ…ط­طھظˆظ‰ (ط§ظ„ظ…ظ‚ط§ظ„ط§طھطŒ ط§ظ„ط£ظ‚ط³ط§ظ…طŒ ط§ظ„ظˆط³ظˆظ…طŒ ط§ظ„ظˆط³ط§ط¦ط·)</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    // Password strength indicator (optional enhancement)
    const passwordInput = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirmation');
    
    // Simple password match validation
    passwordConfirm.addEventListener('input', function() {
        if (passwordInput.value && this.value) {
            if (passwordInput.value === this.value) {
                this.classList.remove('border-red-500');
                this.classList.add('border-green-500');
            } else {
                this.classList.remove('border-green-500');
                this.classList.add('border-red-500');
            }
        }
    });
</script>
@endpush

