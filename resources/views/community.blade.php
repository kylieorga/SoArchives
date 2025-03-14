<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Forum</title>

    {{-- Tab Logo --}}
    <link rel="shortcut icon" href="{{ asset('images/tab-logo.ico') }}" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    {{-- CSS file under Public Folder --}}
    <link rel="stylesheet" href="{{ asset('css/community.css') }}" />

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</head>

<body>

    {{-- Navbar --}}
    <header>
        @include('header_and_footer.header')
    </header>


    <div class="banner" style="background-image: url('images/forr.png'); height:400px"></div>

    <div class="mt-1">
        <div class="row">
        </div>
        <div class="container col-lg-6 mb-4" style="margin-top: -5rem; ">
            <h2 class="title card-title1 mt-5 mb-2">Welcome to Community Forum</h2>
        </div>
        <div class="container col-lg-6 mb-5">

            {{-- Posting --}}
            <div class="actions d-flex justify-content-between mb-2">
                <button id="postButton" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#postModal">
                    + New Post
                </button>
                <div class="d-flex align-items-center ms-auto">

                    {{-- Search bar --}}
                    <input type="text" class="form-control me-2 input-group rounded-pill overflow-hidden"
                        id="searchBar" placeholder="Search..." style="width: 200px;">

                    {{-- Filter topics --}}
                    <button class="btn btn-outline-secondary">
                        <i class="fas fa-filter"></i> Filter Topics
                    </button>
                </div>
            </div>


            {{-- New Post Modal --}}
            <div class="modal fade" id="postModal" tabindex="-1" aria-labelledby="postModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="postModalLabel">Create Post</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="postForm" method="POST" action="{{ route('community.storePost') }}">
                                @csrf

                                {{-- Rich Text Editor --}}
                                <div class="mb-3">
                                    <label for="content" class="form-label"></label>
                                    <div id="quillEditor" style="height: 300px;"></div>
                                    <input type="hidden" name="content" id="hiddenContent">
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Post</button>
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Include Quill -->
            <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
            <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
            <script>
                var quillCreate; // Global variable for creating new posts
                var quillEditInstances = {}; // Object to keep track of Quill instances for editing

                // Initialize Quill editor for creating new post
                document.addEventListener("DOMContentLoaded", function() {
                    quillCreate = new Quill('#quillEditor', {
                        theme: 'snow',
                        modules: {
                            toolbar: [
                                [{
                                    'header': [1, 2, false]
                                }],
                                ['bold', 'italic', 'underline'],
                                [{
                                    'list': 'ordered'
                                }, {
                                    'list': 'bullet'
                                }],
                                ['clean'] // remove formatting button
                            ]
                        }
                    });

                    // Set hidden input with editor content on form submit for creating new post
                    document.getElementById('postForm').addEventListener('submit', function(event) {
                        var content = quillCreate.root.innerHTML;
                        document.getElementById('hiddenContent').value = content;

                        // Debug: Log the content to ensure it's being set
                        console.log('Content to be sent:', content);
                    });
                });

                // Function to initialize Quill editor for editing post
                function initializeQuillEditor(postId) {
                    var editorContainer = document.getElementById('quillEditorEdit' + postId);
                    if (editorContainer) {
                        // Destroy existing Quill instance if it exists
                        if (quillEditInstances[postId]) {
                            quillEditInstances[postId].destroy();
                        }

                        // Create new Quill editor instance
                        quillEditInstances[postId] = new Quill(editorContainer, {
                            theme: 'snow',
                            modules: {
                                toolbar: [
                                    [{
                                        'header': [1, 2, false]
                                    }],
                                    ['bold', 'italic', 'underline'],
                                    [{
                                        'list': 'ordered'
                                    }, {
                                        'list': 'bullet'
                                    }],
                                    ['clean'] // remove formatting button
                                ]
                            }
                        });

                        // Set initial content for edit modal
                        var content = document.getElementById('postContent' + postId).value;
                        quillEditInstances[postId].root.innerHTML = content;

                        // Set hidden input with editor content on form submit for editing post
                        document.getElementById('editPostForm' + postId).addEventListener('submit', function(event) {
                            var editContent = quillEditInstances[postId].root.innerHTML;
                            document.getElementById('hiddenEditContent' + postId).value = editContent;

                            // Debug: Log the content to ensure it's being set
                            console.log('Edited content to be sent:', editContent);
                        });
                    }
                }

                // Function to initialize Quill editor for editing comment
                function initializeQuillCommentEditor(commentId) {
                    var editorContainer = document.getElementById('quillCommentEditorEdit' + commentId);
                    if (editorContainer) {
                        // Destroy existing Quill instance if it exists
                        if (quillCommentEditInstances[commentId]) {
                            quillCommentEditInstances[commentId].destroy();
                        }

                        // Create new Quill editor instance
                        quillCommentEditInstances[commentId] = new Quill(editorContainer, {
                            theme: 'snow',
                            modules: {
                                toolbar: [
                                    [{
                                        'header': [1, 2, false]
                                    }],
                                    ['bold', 'italic', 'underline'],
                                    [{
                                        'list': 'ordered'
                                    }, {
                                        'list': 'bullet'
                                    }],
                                    ['clean'] // remove formatting button
                                ]
                            }
                        });

                        // Set initial content for edit modal
                        var content = document.getElementById('commentContent' + commentId).value;
                        quillCommentEditInstances[commentId].root.innerHTML = content;

                        // Set hidden input with editor content on form submit for editing comment
                        document.getElementById('editCommentForm' + commentId).addEventListener('submit', function(event) {
                            var editContent = quillCommentEditInstances[commentId].root.innerHTML;
                            document.getElementById('hiddenEditCommentContent' + commentId).value = editContent;

                            // Debug: Log the content to ensure it's being set
                            console.log('Edited comment content to be sent:', editContent);
                        });
                    }
                }
            </script>



            <!-- Display Posts -->
            <div class="mt-4">
                @foreach ($posts->reverse() as $post)
                    @if ($post->visible)
                        <!-- Card with clickable functionality -->
                        <div class="card border-0 mb-3 position-relative" style="cursor: pointer;">
                            <div class="card-body">
                                <!-- Profile Image and Name -->
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ asset('images/def.png') }}" alt="Profile Image"
                                        class="rounded-circle me-2" style="width: 40px; height: 40px;">
                                    <div class="d-flex flex-column">
                                        <h6 class="card-title mb-0">{{ $post->user->fname }} {{ $post->user->lname }}
                                        </h6>
                                        <span class="text-muted small">{{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>

                                <!-- Post Title -->
                                {{-- <h4 class="card-title"
                                    style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {!! $post->title !!}
                                </h4> --}}

                                <!-- Post Display-->
                                <p>{!! $post->content !!}</p>

                                <!-- Like and Comment Buttons Row -->
                                <div class="d-flex align-items-center">
                                    <!-- Like Button -->
                                    <form action="{{ route('community.like', $post->id) }}" method="POST"
                                        style="display: inline-block;">
                                        @csrf
                                        @php
                                            $isLiked = $post->likes->contains('user_id', auth()->id());
                                        @endphp
                                        <button
                                            class="btn btn-sm me-2 border-0 {{ $isLiked ? 'btn-outline-success' : 'btn-outline-secondary' }}"
                                            type="submit">
                                            <i class="fas fa-thumbs-up"></i> {{ $post->likes_count }}
                                        </button>
                                    </form>


                                    {{-- Comment Button --}}
                                    <button class="btn btn-sm btn-outline-secondary border-0" data-bs-toggle="collapse"
                                        data-bs-target="#commentsSection{{ $post->id }}">
                                        <i class="fas fa-comment"></i>
                                        <span class="comment-count">{{ $post->comments->count() }}</span>
                                    </button>
                                </div>

                                <!-- Comment Section (collapsible) -->
                                <div class="collapse mt-4" id="commentsSection{{ $post->id }}">



                                    {{-- Comment Form --}}
                                    <div class="comment-form">
                                        <form action="{{ route('community.comment', $post) }}" method="POST">
                                            @csrf
                                            <div class="form-group mt-2 mb-2">
                                                <textarea class="form-control" name="content" rows="2" placeholder="Write your comment here..." required></textarea>
                                            </div>
                                            <div class="text-end mb-2">
                                                <button type="submit" class="btn btn-primary">Comment</button>
                                            </div>
                                        </form>
                                    </div>


                                    <!-- Display Comments -->
                                    <div style="margin-left: 18px;">
                                        @forelse ($post->comments as $comment)
                                            <div class="comment-container mb-3 position-relative">
                                                <div class="d-flex align-items-center mb-1">
                                                    <!-- Reply Icon -->
                                                    <div class="me-2">
                                                        <button
                                                            class="btn btn-outline-secondary btn-sm border-0 disabled"
                                                            style="border-radius: 50%; padding: 0; width: 30px; height: 30px;">
                                                            <i class="fas fa-reply flip-icon"></i>
                                                        </button>
                                                    </div>

                                                    <!-- Profile Image -->
                                                    <img src="{{ asset('images/def.png') }}" alt="Profile Image"
                                                        class="rounded-circle me-2"
                                                        style="width: 40px; height: 40px;">

                                                    <!-- User Info -->
                                                    <div class="d-flex flex-column">
                                                        <h6 class="mb-0">{{ $comment->user->fname }}
                                                            {{ $comment->user->lname }}</h6>
                                                        <span
                                                            class="text-muted small">{{ $comment->created_at->diffForHumans() }}</span>
                                                    </div>

                                                    <!-- Edit and Delete Comment Buttons -->
                                                    <div class="ms-auto">
                                                        @if (auth()->check() && auth()->id() == $comment->user_id)
                                                            <button class="btn btn-sm btn-outline-primary border-0"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editCommentModal{{ $comment->id }}">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <form id="deleteCommentForm{{ $comment->id }}"
                                                                action="{{ route('community.deleteComment', $comment) }}"
                                                                method="POST" style="display: inline-block;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button"
                                                                    class="btn btn-sm btn-outline-danger border-0"
                                                                    onclick="confirmDelete(event, 'deleteCommentForm{{ $comment->id }}')">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>


                                                </div>
                                                <p class="mt-3 ms-5 mb-2">{{ $comment->content }}</p>
                                            </div>


                                            <!-- Edit Comment Modal -->
                                            <div class="modal fade" id="editCommentModal{{ $comment->id }}"
                                                tabindex="-1"
                                                aria-labelledby="editCommentModalLabel{{ $comment->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="editCommentModalLabel{{ $comment->id }}">Edit
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form
                                                                action="{{ route('community.updateComment', $comment) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="form-group">
                                                                    <textarea class="form-control mb-3" id="commentContent{{ $comment->id }}" name="content" rows="5" required>{{ $comment->content }}</textarea>
                                                                </div>
                                                                <button type="submit" class="btn btn-primary">Save
                                                                    Changes</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <!-- No Comments Message -->
                                            <p class="text-muted">No comments yet</p>
                                        @endforelse
                                    </div>

                                    {{-- Show more: Hindi pa to nagana, UI lang --}}
                                    <a href="#" class="btn btn-primary btn-sm btn-block" role="button"><span
                                            class="glyphicon glyphicon-refresh"></span>Show More</a>

                                </div>


                                {{-- Edit Post Modal --}}
                                <div class="modal fade" id="editPostModal{{ $post->id }}" tabindex="-1"
                                    aria-labelledby="editPostModalLabel{{ $post->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editPostModalLabel{{ $post->id }}">
                                                    Edit Post</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="editPostForm{{ $post->id }}" method="POST"
                                                    action="{{ route('community.updatePost', $post->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    {{-- Rich Text Editor for Edit --}}
                                                    <div class="mb-3">
                                                        <label for="content" class="form-label"></label>
                                                        <div id="quillEditorEdit{{ $post->id }}"
                                                            style="height: 300px;"></div>
                                                        <input type="hidden" name="content"
                                                            id="hiddenEditContent{{ $post->id }}">
                                                    </div>
                                                    <div class="text-end">
                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Hidden input field to store post content for edit modal -->
                                <input type="hidden" id="postContent{{ $post->id }}"
                                    value="{!! $post->content !!}">
                                <script>
                                    // Initialize Quill editor for this specific post when the modal is shown
                                    document.getElementById('editPostModal{{ $post->id }}').addEventListener('shown.bs.modal', function() {
                                        initializeQuillEditor({{ $post->id }});
                                    });
                                </script>



                                {{-- Wala pang topics, ui lang to --}}
                                <!-- Topic Tags and Edit/Delete Post Buttons -->
                                <div
                                    class="d-flex justify-content-end align-items-start position-absolute top-0 end-0 p-3">
                                    <div>
                                        @if ($post->tags && $post->tags->count() > 0)
                                            @foreach ($post->tags as $tag)
                                                <span class="badge bg-secondary">{{ $tag->name }}</span>
                                            @endforeach
                                        @else
                                            <span class="badge bg-light text-dark p-2 fw-normal">Topic Tag</span>
                                        @endif
                                    </div>
                                    <div class="ms-2">
                                        @if (auth()->check() && auth()->id() == $post->user_id)
                                            <button class="btn btn-sm btn-outline-primary border-0"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editPostModal{{ $post->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form id="deletePostForm{{ $post->id }}"
                                                action="{{ route('community.deletePost', $post) }}" method="POST"
                                                style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-outline-danger border-0"
                                                    onclick="confirmDelete(event, 'deletePostForm{{ $post->id }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>


            {{-- JS: Deletion confirmation --}}
            <script>
                function confirmDelete(event, formId) {
                    event.preventDefault(); // Prevent the default form submission

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d11a2a',
                        cancelButtonColor: '#6e7681',
                        confirmButtonText: 'Delete',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(formId).submit(); // Submit the form if confirmed
                        }
                    });
                }
            </script>


            {{-- UI: Pagination --}}
            <div class="card border-0 pe-4">
                <nav aria-label="..." class="mt-3">
                    <ul class="pagination justify-content-end">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item" aria-current="page">
                            <a class="page-link" href="#">2</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>


            {{-- UI: Back to top button --}}
            <button type="button" class="btn btn-primary btn-floating btn-lg" id="btn-back-to-top">
                <i class="fas fa-arrow-up"></i>
            </button>



            {{-- JS: Back to top button --}}
            <script>
                //Get the button
                let mybutton = document.getElementById("btn-back-to-top");

                // When the user scrolls down 20px from the top of the document, show the button
                window.onscroll = function() {
                    scrollFunction();
                };

                function scrollFunction() {
                    if (
                        document.body.scrollTop > 20 ||
                        document.documentElement.scrollTop > 20
                    ) {
                        mybutton.style.display = "block";
                    } else {
                        mybutton.style.display = "none";
                    }
                }
                // When the user clicks on the button, scroll to the top of the document
                mybutton.addEventListener("click", backToTop);

                function backToTop() {
                    document.body.scrollTop = 0;
                    document.documentElement.scrollTop = 0;
                }
            </script>

        </div>



        <footer>
            @include('header_and_footer.footer')
        </footer>

        <!-- Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Font Awesome -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"></script>

</body>

</html>
