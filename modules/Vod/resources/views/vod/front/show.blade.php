@extends('layouts.blog')

@section('title', $content->title)

@section('description', \Illuminate\Support\Str::limit(strip_tags($content->description), 155))

@section('content')
<div class="container mx-auto px-4 py-8 max-w-5xl">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-3 text-lg text-gray-500 mb-6 px-1">
        <a href="{{ route('home') }}" class="hover:text-gray-900 transition-colors">الرئيسية</a>
        <span class="text-gray-300">/</span>
        <a href="{{ $content->type == 'video' ? route('videos.index') : route('audios.index') }}" class="hover:text-gray-900 transition-colors">
            {{ $content->type == 'video' ? 'مكتبة الفيديو' : 'مكتبة الصوتيات' }}
        </a>
        <span class="text-gray-300">/</span>
        <span class="text-gray-900 font-medium truncate max-w-[300px]">{{ $content->title }}</span>
    </nav>

    {{-- Main Content Group --}}
    <div class="flex flex-col">
        {{-- Video Player --}}
        <div class="w-full aspect-video bg-gray-900 rounded-2xl overflow-hidden shadow-2xl z-10 [&_iframe]:w-full [&_iframe]:h-full [&_iframe]:border-0">
            {!! $content->embed_html !!}
        </div>

        {{-- Info Section --}}
        <div class="bg-gray-50 rounded-b-2xl rounded-t-lg -mt-2 pt-8 pb-10 px-6 md:px-10 relative z-0">
            {{-- Header --}}
            <div class="max-w-4xl">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 leading-snug mb-4">
                    {{ $content->title }}
                </h1>

                {{-- Metadata Row --}}
                <div class="flex flex-wrap items-center gap-3 text-lg text-gray-600">
                    {{-- Author --}}
                    @if($content->author)
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden text-xs font-bold text-gray-500">
                            {{ substr($content->author->name, 0, 1) }}
                        </div>
                        <span class="font-medium text-gray-900">{{ $content->author->name }}</span>
                    </div>
                    <span class="text-gray-300">•</span>
                    @endif

                    {{-- Date --}}
                    @if($content->published_at)
                    <span class="dir-ltr">{{ $content->published_at->translatedFormat('d F Y') }}</span>
                    <span class="text-gray-300">•</span>
                    @endif

                    {{-- Views --}}
                    <div class="flex items-center gap-1.5">
                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span>{{ number_format($content->views_count) }} مشاهدة</span>
                    </div>
                </div>
            </div>

            {{-- Divider --}}
            <div class="h-px bg-gray-200 w-full my-8"></div>

            {{-- Description --}}
            <div class="prose prose-xl text-gray-700 leading-loose max-w-4xl prose-headings:font-bold prose-headings:text-gray-900 prose-a:text-brand-primary hover:prose-a:text-brand-accent prose-img:rounded-xl">
                {!! $content->description !!}
            </div>
        </div>
    </div>
</div>
@endsection
