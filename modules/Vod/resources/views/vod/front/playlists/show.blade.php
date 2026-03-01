@extends('layouts.blog')

@section('title', $playlist->title)

@section('content')
<div class="container mx-auto px-4 py-12 max-w-6xl">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-8">
        <a href="{{ route('home') }}" class="hover:text-brand-primary transition-colors">الرئيسية</a>
        <span class="text-gray-300">/</span>
        <a href="{{ $playlist->type == 'video' ? route('videos.index') : route('audios.index') }}" class="hover:text-brand-primary transition-colors">
            {{ $playlist->type == 'video' ? 'مكتبة الفيديو' : 'مكتبة الصوتيات' }}
        </a>
        <span class="text-gray-300">/</span>
        <a href="{{ ($playlist->type == 'video' ? route('videos.index') : route('audios.index')) . '?tab=playlists' }}" class="hover:text-brand-primary transition-colors">
            {{ $playlist->type == 'video' ? 'السلاسل' : 'الألبومات' }}
        </a>
        <span class="text-gray-300">/</span>
        <span class="text-gray-700 font-medium truncate max-w-[200px]">{{ $playlist->title }}</span>
    </nav>

    <div class="flex flex-col lg:flex-row gap-8 lg:gap-12 items-start">
        
        {{-- Right Column (Videos List) --}}
        {{-- In RTL, this appears on the Right side as requested --}}
        <div class="w-full lg:w-2/3 order-2 lg:order-1">
            <h2 class="text-2xl font-serif font-bold text-gray-900 mb-6 flex items-center gap-2">
                <span>محتويات {{ $playlist->type == 'video' ? 'السلسلة' : 'الألبوم' }}</span>
                <span class="bg-gray-100 text-brand-primary text-sm font-sans font-bold px-2 py-1 rounded-full">{{ $playlist->items->count() }}</span>
            </h2>

            <div class="space-y-4">
                @forelse($playlist->items as $index => $item)
                    <article class="group flex items-start gap-4 p-4 bg-white border border-gray-100 rounded-xl hover:border-gray-200 hover:shadow-sm transition-all duration-300">
                        {{-- Number --}}
                        <div class="hidden sm:flex items-center justify-center w-8 h-8 rounded-full bg-gray-50 text-gray-400 font-bold text-sm shrink-0 group-hover:bg-brand-primary group-hover:text-white transition-colors mt-2">
                            {{ $index + 1 }}
                        </div>

                        {{-- Thumbnail --}}
                        <a href="{{ $item->route }}" class="block relative w-32 sm:w-40 aspect-video bg-gray-100 rounded-lg overflow-hidden shrink-0">
                            @if($item->thumbnail_path)
                                <img src="{{ Storage::url($item->thumbnail_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-200">
                                    <svg class="w-8 h-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-brand-primary/0 group-hover:bg-brand-primary/10 transition-colors flex items-center justify-center">
                                <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity transform scale-75 group-hover:scale-100 drop-shadow-md" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </a>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0 py-1">
                            <h3 class="text-lg font-bold font-serif text-gray-900 leading-tight mb-2 group-hover:text-brand-accent transition-colors">
                                <a href="{{ $item->route }}" class="line-clamp-2">
                                    {{ $item->title }}
                                </a>
                            </h3>
                            <div class="flex items-center gap-3 text-xs text-gray-500">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    {{ number_format($item->views_count) }}
                                </span>
                                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                <span>{{ $item->published_at ? $item->published_at->format('Y/m/d') : '' }}</span>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="p-8 text-center bg-gray-50 rounded-xl border border-dashed border-gray-200">
                        <p class="text-gray-500">لا يوجد محتوى في هذه القائمة بعد.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Left Column (Playlist Info) --}}
        {{-- In RTL, this appears on the Left side as requested --}}
        <div class="w-full lg:w-1/3 order-1 lg:order-2">
            <div class="sticky top-24">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                    {{-- Cover Image --}}
                    @php $firstItem = $playlist->items->first(); @endphp
                    <div class="aspect-video bg-gray-200 rounded-xl overflow-hidden mb-6 shadow-sm relative">
                        @if($firstItem && $firstItem->thumbnail_path)
                            <img src="{{ Storage::url($firstItem->thumbnail_path) }}" alt="{{ $playlist->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                @if($playlist->type == 'video')
                                    <svg class="w-16 h-16 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                                @else
                                    <svg class="w-16 h-16 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                                @endif
                            </div>
                        @endif
                        
                        {{-- Type Badge --}}
                        <div class="absolute top-3 right-3 bg-brand-primary text-white text-xs font-bold px-2 py-1 rounded shadow-sm">
                            {{ $playlist->type == 'video' ? 'سلسلة فيديو' : 'ألبوم صوتي' }}
                        </div>
                    </div>

                    <h1 class="text-2xl font-serif font-bold text-gray-900 mb-4 leading-tight">
                        {{ $playlist->title }}
                    </h1>

                    @if($playlist->description)
                        <div class="prose prose-sm text-gray-600 mb-6 leading-relaxed">
                            {!! $playlist->description !!}
                        </div>
                    @endif

                    <div class="border-t border-gray-200 pt-4 mt-2">
                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <span>عدد الحلقات</span>
                            <span class="font-bold text-gray-900">{{ $playlist->items->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm text-gray-500 mt-2">
                            <span>تاريخ الإنشاء</span>
                            <span class="font-bold text-gray-900">{{ $playlist->created_at->format('Y/m/d') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
