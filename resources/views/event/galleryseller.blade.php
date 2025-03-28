<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events Gallery</title>

    <link href="/bootstrap-5.3.3-dist/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">

    <!-- to work the toggle in the navbar -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/regular.min.css" integrity="sha512-KYEnM30Gjf5tMbgsrQJsR0FSpufP9S4EiAYi168MvTjK6E83x3r6PTvLPlXYX350/doBXmTFUEnJr/nCsDovuw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Animate CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" integrity="sha512-c42qTSw/wPZ3/5LBzD+Bw5f7bSF2oxou6wEb+I/lqeaKV5FDIfMvvRp772y4jcJLKuGUOpbJMdg/BTl50fJYAw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
</head>
<body>
    <header>
        @include('header_and_footer.header')
    </header>

    @include('layouts.eventsHeader')

    <div class="container custom-shadow mt-1 p-3 mb-1">
    <div class="row">
        @foreach($events as $event)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="{{ asset('storage/' . $event->EventImage) }}" class="card-img-top" alt="Event Image">
                    <div class="card-body">
                        <h5 class="card-title">{{ $event->EventName }}</h5>
                        @if ($event->UserID === $currentUserId)
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#UploadImageModal" data-event-id="{{ $event->id }}">
                            Upload Event Images
                        </button>
                        @endif
                        <button type="button" class="btn btn-secondary" data-url="{{ route('gallery.showEventImages', ['eventId' => $event->EventID]) }}">
                            View Event Images
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-secondary').forEach(function(button) {
        button.addEventListener('click', function() {
            var url = button.getAttribute('data-url');
            if (url) {
                window.location.href = url;
            } else {
                console.error('No URL found for button');
            }
        });
    });
});
</script>


    <!-- Upload Image Modal -->
    <div class="modal fade" id="UploadImageModal" tabindex="-1" aria-labelledby="UploadImageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="UploadImageModalLabel">Upload Images</h1>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('gallery.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="EventID" id="uploadEventId">
                        <div class="mb-3">
                            <label for="gallery" class="form-label">Click here to add Images:</label>
                            <input type="file" class="form-control" id="gallery" name="gallery[]" accept="image/*" multiple required>
                            <p class="text-muted">Accepted image formats: .jpeg, .jpg, .png, with a maximum size of 2MB per image.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    

    <footer>
        @include('header_and_footer.footer')
    </footer>
</body>
</html>
