{{-- 
    Biography is injected via App\View\Composers\OwnerBioComposer
    Theme Boundary: Views must NEVER execute database queries directly.
--}}
@if($biography)
  <div class="owner-bio prose prose-lg max-w-none">
    {!! $biography !!}
  </div>
@endif

