@once
<!-- CKEditor 5 Scripts -->
<script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('vendor/ckeditor/translations/ar.js') }}"></script>

<style>
    /* CKEditor Base Styles */
    .ckeditor {
        min-height: 200px;
    }
    
    .ck-editor__editable {
        line-height: 1.8 !important;
        font-size: 16px !important;
        padding: 20px !important;
    }

    /* PROFILE: Default (Article) */
    .ck-editor-default .ck-editor__editable {
        min-height: 600px !important;
        max-height: 85vh !important;
    }

    /* PROFILE: Compact (Short Description) */
    .ck-editor-compact .ck-editor__editable {
        min-height: 150px !important;
        max-height: 300px !important;
        overflow-y: auto !important;
        padding: 15px !important;
    }
    
    /* Content Styles */
    .ck-content {
        font-family: 'Cairo', sans-serif;
    }
    .ck-content ul { list-style-type: disc; padding-right: 20px; }
    .ck-content ol { list-style-type: decimal; padding-right: 20px; }
    .ck-content h2 { font-size: 1.5em; font-weight: bold; margin: 1em 0; }
    .ck-content h3 { font-size: 1.17em; font-weight: bold; margin: 0.8em 0; }
    .ck-content p { margin-bottom: 0.8em; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const initializedEditors = new WeakSet();
    
    class CustomImageUploadAdapter {
        constructor(loader) { this.loader = loader; }
        upload() {
            return this.loader.file.then(file => new Promise((resolve, reject) => {
                const data = new FormData();
                data.append('upload', file);
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                if (csrfToken) data.append('_token', csrfToken);

                fetch('{{ route("ckeditor.upload") }}', { 
                    method: 'POST', body: data, headers: { 'X-CSRF-TOKEN': csrfToken || '' }
                })
                .then(r => r.json())
                .then(d => d.url ? resolve({ default: d.url }) : reject(d.error || 'فشل'))
                .catch(e => reject('خطأ'));
            }));
        }
        abort() {}
    }

    function CustomImageUploadAdapterPlugin(editor) {
        editor.plugins.get('FileRepository').createUploadAdapter = (loader) => new CustomImageUploadAdapter(loader);
    }

    function initializeCKEditors() {
        const textareas = document.querySelectorAll('textarea.ckeditor');
        
        textareas.forEach(textarea => {
            if (initializedEditors.has(textarea)) return;
            initializedEditors.add(textarea);
            
            const profile = textarea.dataset.profile || 'default';
            
            // Define Toolbars based on Profile
            const toolbars = {
                default: [
                    'heading', '|',
                    'bold', 'italic', 'underline', 'link', 'blockQuote', 'insertTable', '|',
                    'bulletedList', 'numberedList', 'outdent', 'indent', '|',
                    'mediaEmbed', 'imageUpload', 'undo', 'redo'
                ],
                compact: [
                    'bold', 'italic', 'link', '|',
                    'bulletedList', 'numberedList', '|',
                    'undo', 'redo'
                ]
            };

            ClassicEditor.create(textarea, {
                language: { ui: 'ar', content: 'ar' },
                placeholder: textarea.dataset.placeholder || 'ابدأ الكتابة...',
                extraPlugins: [CustomImageUploadAdapterPlugin],
                toolbar: {
                    shouldNotGroupWhenFull: true,
                    items: toolbars[profile] || toolbars.default
                },
                mediaEmbed: { previewsInData: true }
            })
            .then(editor => {
                textarea.ckeditorInstance = editor;
                
                // Add Profile Class to the Main Editor Container
                // .ck-editor is the wrapper created by CKEditor next to textarea
                const editorElement = editor.ui.view.element;
                if (editorElement) {
                    editorElement.classList.add('ck-editor-' + profile);
                }

                // Sync Logic
                const form = textarea.closest('form');
                if (form && !form.ckeditorSyncAttached) {
                    form.ckeditorSyncAttached = true;
                    form.addEventListener('submit', function() {
                        form.querySelectorAll('textarea.ckeditor').forEach(ta => {
                            if (ta.ckeditorInstance) ta.ckeditorInstance.updateSourceElement();
                        });
                    });
                }
            })
            .catch(error => {
                console.error(error);
                initializedEditors.delete(textarea);
            });
        });
    }
    
    initializeCKEditors();
    window.initializeCKEditors = initializeCKEditors;
});
</script>
@endonce
