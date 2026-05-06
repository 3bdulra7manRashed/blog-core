@extends('layouts.blog')

@section('title', $title)

@section('content')
    <div class="container mx-auto px-4 py-12 max-w-6xl">
        {{-- Header Section --}}
        <div class="mb-12 flex flex-col items-center justify-center text-center">
            <h1 class="mb-2 text-3xl md:text-4xl font-bold text-[var(--brand-primary)]">{{ $title }}</h1>
            <p class="mb-8 text-lg md:text-xl text-gray-500">أحدث {{ $type === 'video' ? 'المقاطع المرئية' : 'المقاطع الصوتية' }}
                والسلاسل</p>

            {{-- Tabs --}}
            @if(config('features.vod.playlists'))
                <div class="inline-flex rounded-lg bg-gray-100 p-1">
                    <a href="{{ $type === 'video' ? route('videos.index') : route('audios.index') }}"
                        class="rounded-md px-6 py-2.5 text-lg font-bold transition-all {{ $currentTab !== 'playlists' ? 'bg-brand-accent text-brand-secondary shadow-sm hover:bg-brand-primary hover:text-white' : 'text-gray-500 hover:text-gray-700' }}">
                        {{ $type === 'video' ? 'كل الفيديوهات' : 'كل الصوتيات' }}
                    </a>
                    <a href="{{ ($type === 'video' ? route('videos.index') : route('audios.index')) . '?tab=playlists' }}"
                        class="rounded-md px-6 py-2.5 text-lg font-bold transition-all {{ $currentTab === 'playlists' ? 'bg-brand-accent text-brand-secondary shadow-sm hover:bg-brand-primary hover:text-white' : 'text-gray-500 hover:text-gray-700' }}">
                        {{ $type === 'video' ? 'سلاسل الفيديو' : 'الألبومات الصوتية' }}
                    </a>
                </div>
            @endif
        </div>

        {{-- Content Grid --}}
        @if($contents->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($contents as $item)
                    @php
                        $isPlaylist = isset($currentTab) && $currentTab === 'playlists';
                        $route = $isPlaylist ? route('videos.playlists.show', $item->slug) : $item->route;
                        $title = $item->title;
                        $thumbnail = $isPlaylist ? ($item->items->first()?->thumbnail_path ?? null) : $item->thumbnail_path;
                        $date = $item->created_at->translatedFormat('F Y');
                        $count = $isPlaylist ? $item->items_count : null;
                        $typeLabel = $type === 'video' ? 'فيديوهات' : 'مقاطع';
                    @endphp

                    <div class="group flex flex-col gap-3">
                        {{-- Thumbnail --}}
                        <a href="{{ $route }}"
                            class="block relative aspect-video w-full overflow-hidden rounded-xl bg-gray-100 shadow-sm transition-all duration-300 hover:shadow-md">
                            @if($thumbnail)
                                <img src="{{ Storage::url($thumbnail) }}" alt="{{ $title }}"
                                    class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105">
                            @else
                                <div class="flex h-full w-full items-center justify-center bg-gray-200 text-gray-400">
                                    <svg class="h-12 w-12 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                                        </path>
                                    </svg>
                                </div>
                            @endif

                            {{-- Play Overlay --}}
                            <div
                                class="absolute inset-0 flex items-center justify-center bg-brand-primary/20 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-full bg-white/90 shadow-lg transform scale-90 transition-transform duration-300 group-hover:scale-100">
                                    <svg class="h-5 w-5 text-[var(--brand-primary)] ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"></path>
                                    </svg>
                                </div>
                            </div>

                            {{-- 16:9 Badge --}}
                            <div
                                class="absolute bottom-2 right-2 rounded bg-brand-primary/70 px-1.5 py-0.5 text-[10px] font-bold text-white">
                                16:9
                            </div>
                        </a>

                        {{-- Content --}}
                        <div class="flex flex-col gap-1.5">
                            <h3
                                class="text-2xl font-bold leading-snug text-[var(--brand-primary)] line-clamp-2 group-hover:text-brand-accent transition-colors">
                                <a href="{{ $route }}">{{ $title }}</a>
                            </h3>
                            <div class="flex items-center gap-2 text-lg text-gray-500">
                                @if($isPlaylist)
                                    <span>{{ $count }} {{ $typeLabel }}</span>
                                    <span class="h-1 w-1 rounded-full bg-gray-300"></span>
                                    <span>{{ $date }}</span>
                                @else
                                    <span>{{ $item->published_at ? $item->published_at->translatedFormat('F Y') : $date }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-16">
                {{ $contents->links() }}
            </div>
        @else
            <div class="text-center py-20 border border-dashed border-gray-200 rounded-xl bg-gray-50">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                </svg>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">لا يوجد محتوى حالياً</h3>
                <p class="text-gray-500 text-xl">لم تُضَف أي {{ $type === 'video' ? 'فيديوهات' : 'صوتيات' }} بعد.</p>
            </div>
        @endif
    </div>
@endsection