@extends('layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h3>Video Players</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <form
                                action="{{ isset($videoplayer) ? route('video-players.update', $videoplayer->id) : route('video-players.store') }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @if (isset($videoplayer))
                                    @method('PUT')
                                @endif
                                <div class="mb-3">
                                    <label for="url" class="form-label">Video URL</label>
                                    <input type="text" class="form-control" id="url" name="url"
                                        placeholder="Enter video URL"
                                        value="{{ isset($videoplayer) ? $videoplayer->url : old('url') }}">
                                    @error('url')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
