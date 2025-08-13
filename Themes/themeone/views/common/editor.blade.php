
 @if(config('app.editor_type') === 'quilljs')

<style>
 /* Force tooltip inside editor container */
.quill-editor-container {
    position: relative; /* anchor for tooltip */
}

.quill-editor-container .ql-tooltip.ql-editing {
    position: absolute !important;  /* place relative to container */
    top: 40px !important;           /* space below toolbar */
    left: 10px !important;          /* small horizontal offset */
    transform: none !important;     /* remove Quill's default transform */
    width: auto;
    max-width: calc(100% - 20px);   /* keep inside container */
    z-index: 1000;                  /* above other content */
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Select all textareas with class rich-editor
  document.querySelectorAll('textarea.ckeditor').forEach(function(textarea) {
    // Check if QuillJS is already initialized by the custom directive
    if (textarea.quillInitialized) {
      console.log('Skipping QuillJS initialization - already handled by custom directive');
      return;
    }
    
    // Hide the original textarea but keep it in DOM for form submission
    textarea.style.display = 'none';

    // Create a div for Quill editor after the textarea
    var quillDiv = document.createElement('div');
    quillDiv.style.height = '200px';
    quillDiv.classList.add('quill-editor-container');
    textarea.parentNode.insertBefore(quillDiv, textarea.nextSibling);

    // Initialize Quill on that div
    var quill = new Quill(quillDiv, {
      theme: 'snow',
      modules: {
        toolbar: [
            [{ font: [] }],
            [{ size: ['small', false, 'large', 'huge'] }], // custom sizes
            ['bold', 'italic', 'underline', 'strike'],    // toggled buttons
            [{ color: [] }, { background: [] }],          // dropdown with defaults
            [{ script: 'sub' }, { script: 'super' }],     // superscript/subscript
            [{ header: '1' }, { header: '2' }, 'blockquote', 'code-block'],
            [{ list: 'ordered' }, { list: 'bullet' }],
            [{ indent: '-1' }, { indent: '+1' }],         // outdent/indent
            ['link', 'image', 'video', 'formula'],
            ['clean']  
        ],
        formula: true
      }
    });

    // Load existing content from textarea into Quill
    quill.root.innerHTML = textarea.value;

    // On form submit, copy Quill content back to textarea
    textarea.form.addEventListener('submit', function() {
      textarea.value = quill.root.innerHTML;
    });
  });
});
</script>

@else
<script src="{{themes('plugins/ckeditor-standard/ckeditor.js')}}"></script>
@endif

<script>
  window.editorType = "{{ config('app.editor_type') }}";
</script>
