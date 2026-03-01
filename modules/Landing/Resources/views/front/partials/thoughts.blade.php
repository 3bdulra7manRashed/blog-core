{{-- Thoughts Section (Stories-style horizontal scroll + Story Modal) --}}
<section class="py-10">
    <div class="container mx-auto px-4 max-w-6xl">
        <div class="flex gap-6 overflow-x-auto pb-4 scrollbar-hide">
            @foreach($thoughts as $index => $thought)
                <div class="flex flex-col items-center w-24 shrink-0 cursor-pointer js-story-trigger"
                    data-index="{{ $index }}">
                    <div class="relative flex items-center justify-center">

                        {{-- Outer Theme Ring --}}
                        <div class="p-[2px] rounded-full bg-brand-accent">

                            {{-- White Gap --}}
                            <div class="p-[2px] bg-white rounded-full">

                                {{-- Image --}}
                                <img src="{{ $thought->image ? asset('storage/' . $thought->image) : asset('images/default-avatar.png') }}"
                                    alt="{{ $thought->title }}" class="w-20 h-20 rounded-full object-cover">

                            </div>
                        </div>

                    </div>
                    <span class="text-xs mt-2 text-center line-clamp-2">
                        {{ $thought->title }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Story Modal --}}
<div id="storyModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-brand-primary/50 backdrop-blur-sm p-0 sm:p-4 transition-opacity duration-300 opacity-0"
    style="will-change: opacity;">

    {{-- Modal Card --}}
    <div id="storyCard"
        class="relative w-full h-full sm:h-auto sm:max-h-[90vh] sm:max-w-5xl lg:max-w-6xl bg-white rounded-none sm:rounded-2xl shadow-2xl overflow-hidden transform transition-all duration-300 scale-95 translate-y-4 opacity-0"
        style="will-change: transform, opacity;">

        {{-- Close Button --}}
        <button id="storyClose"
            class="absolute top-4 left-4 z-20 w-10 h-10 sm:w-8 sm:h-8 flex items-center justify-center rounded-full bg-gray-100/80 hover:bg-gray-200 text-gray-600 hover:text-gray-900 transition-colors text-2xl sm:text-xl leading-none"
            aria-label="إغلاق">
            &times;
        </button>

        {{-- Progress Bar --}}
        <div id="storyProgress" class="absolute top-0 right-0 left-0 z-10 flex gap-1 px-3 pt-2">
            @foreach($thoughts as $i => $t)
                <div class="flex-1 h-[3px] rounded-full bg-gray-300/60 overflow-hidden">
                    <div class="h-full bg-brand-accent rounded-full transition-all duration-300 story-progress-fill"
                        data-index="{{ $i }}" style="width: 0%"></div>
                </div>
            @endforeach
        </div>

        {{-- Story Title --}}
        <div class="p-8 border-b text-center">
            <h3 id="storyTitle" class="text-3xl sm:text-4xl font-serif font-bold text-brand-primary"></h3>
        </div>

        {{-- Story Content --}}
        <div id="storyBody" class="p-8 sm:p-10 overflow-y-auto" style="max-height: 70vh;">
            <div id="storyContent"
                class="text-lg sm:text-xl text-gray-700 leading-loose text-center prose prose-lg max-w-none mx-auto">
            </div>
        </div>

        {{-- Navigation: Previous (Right side for RTL) --}}
        <button id="prevStory"
            class="absolute right-3 top-1/2 -translate-y-1/2 z-20 w-10 h-10 flex items-center justify-center rounded-full bg-white/80 hover:bg-white shadow-md text-gray-600 hover:text-gray-900 transition-all opacity-0 sm:opacity-60 hover:opacity-100 text-xl"
            aria-label="السابق">
            &#8250;
        </button>

        {{-- Navigation: Next (Left side for RTL) --}}
        <button id="nextStory"
            class="absolute left-3 top-1/2 -translate-y-1/2 z-20 w-10 h-10 flex items-center justify-center rounded-full bg-white/80 hover:bg-white shadow-md text-gray-600 hover:text-gray-900 transition-all opacity-0 sm:opacity-60 hover:opacity-100 text-xl"
            aria-label="التالي">
            &#8249;
        </button>
    </div>
</div>

{{-- Story Data (no DB query — data already passed from controller) --}}
@php
    $storyPayload = $thoughts->map(function ($t) {
        return [
            'title' => $t->title,
            'content' => $t->content,
        ];
    })->values();
@endphp
<script>
    window.storyData = @json($storyPayload);
</script>

{{-- Story Modal Logic --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var stories = window.storyData || [];
        if (!stories.length) return;

        var modal = document.getElementById('storyModal');
        var card = document.getElementById('storyCard');
        var closeBtn = document.getElementById('storyClose');
        var prevBtn = document.getElementById('prevStory');
        var nextBtn = document.getElementById('nextStory');
        var titleEl = document.getElementById('storyTitle');
        var contentEl = document.getElementById('storyContent');
        var fills = document.querySelectorAll('.story-progress-fill');

        var currentIndex = 0;
        var isOpen = false;

        // ── Open Story ──
        function openStory(index) {
            currentIndex = index;
            var story = stories[index];

            titleEl.textContent = story.title;
            contentEl.innerHTML = story.content || '';

            // Update progress bar
            fills.forEach(function (fill) {
                var i = parseInt(fill.getAttribute('data-index'));
                if (i < index) fill.style.width = '100%';
                else if (i === index) fill.style.width = '100%';
                else fill.style.width = '0%';
            });

            // Navigation visibility
            prevBtn.style.visibility = stories.length > 1 ? 'visible' : 'hidden';
            nextBtn.style.visibility = stories.length > 1 ? 'visible' : 'hidden';

            if (!isOpen) {
                // Entrance animation
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';

                requestAnimationFrame(function () {
                    modal.classList.remove('opacity-0');
                    card.classList.remove('scale-95', 'translate-y-4', 'opacity-0');
                });

                isOpen = true;
            }
        }

        // ── Close Story ──
        function closeStory() {
            if (!isOpen) return;

            // Exit animation
            modal.classList.add('opacity-0');
            card.classList.add('scale-95', 'translate-y-4', 'opacity-0');

            setTimeout(function () {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
                isOpen = false;
            }, 300);
        }

        // ── Navigation ──
        function nextStory() {
            if (!isOpen) return;
            currentIndex = (currentIndex + 1) % stories.length;
            openStory(currentIndex);
        }

        function prevStory() {
            if (!isOpen) return;
            currentIndex = (currentIndex - 1 + stories.length) % stories.length;
            openStory(currentIndex);
        }

        // ── Event Listeners: Triggers ──
        document.querySelectorAll('.js-story-trigger').forEach(function (trigger) {
            trigger.addEventListener('click', function () {
                var idx = parseInt(this.getAttribute('data-index'));
                openStory(idx);
            });
        });

        // ── Event Listeners: Close ──
        closeBtn.addEventListener('click', closeStory);

        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeStory();
        });

        // ── Event Listeners: Navigation ──
        nextBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            nextStory();
        });

        prevBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            prevStory();
        });

        // ── Keyboard Support ──
        document.addEventListener('keydown', function (e) {
            if (!isOpen) return;

            if (e.key === 'Escape') closeStory();
            if (e.key === 'ArrowLeft') nextStory();   // Left = next in RTL
            if (e.key === 'ArrowRight') prevStory();   // Right = prev in RTL
        });

        // ── Touch/Swipe Support (Mobile) ──
        var touchStartX = 0;
        var touchStartY = 0;

        card.addEventListener('touchstart', function (e) {
            touchStartX = e.changedTouches[0].screenX;
            touchStartY = e.changedTouches[0].screenY;
        }, { passive: true });

        card.addEventListener('touchend', function (e) {
            var diffX = e.changedTouches[0].screenX - touchStartX;
            var diffY = e.changedTouches[0].screenY - touchStartY;

            // Only respond to horizontal swipes (not vertical scroll)
            if (Math.abs(diffX) < 50 || Math.abs(diffY) > Math.abs(diffX)) return;

            if (diffX > 0) prevStory();   // Swipe right = prev in RTL
            if (diffX < 0) nextStory();   // Swipe left  = next in RTL
        }, { passive: true });
    });
</script>