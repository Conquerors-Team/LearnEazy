<!-- @ -0,0 +1,52 @@
@if(config('app.editor_type') === 'quilljs')

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Select all textareas with class rich-editor
  document.querySelectorAll('textarea.ckeditor').forEach(function(textarea) {
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
          ['bold', 'italic', 'underline'],
          ['formula'],
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
    @include('common.editor');
@endif -->
