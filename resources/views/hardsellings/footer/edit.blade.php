@extends('layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">

            <!-- PAGE HEADER -->
            <div class="page-header mb-4">
                <h3>Edit Hardselling Footer</h3>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <form action="{{ route('hardselling-footers.update', $hardsellingFooter->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="card">
                            <div class="card-body">
                                {{-- BACKGROUND --}}
                                <div class="mb-3">
                                    <label class="form-label">Background Color</label>

                                    <div class="d-flex align-items-center gap-3">
                                        <input type="color" class="form-control form-control-color" name="background"
                                            value="{{ old('background', $hardsellingFooter->background_color) }}"
                                            title="Choose background color" required>

                                        <span class="text-muted">Pick background color</span>
                                    </div>
                                </div>


                                <hr>

                                {{-- FOOTER TEXT --}}
                                <div class="mb-3">
                                    <label class="form-label">Footer Text</label>
                                    <input type="text" class="form-control" name="footer_text"
                                        placeholder="Enter footer text"
                                        value="{{ old('footer_text', $hardsellingFooter->footer_text) }}" required>
                                </div>


                                <div class="row d-flex justify-content-center mb-3">
                                    <div class="col-md-6">
                                        {{-- YOUTUBE --}}
                                        <div class="mb-3">
                                            <label class="form-label">Youtube Link</label>
                                            <input type="text" class="form-control" name="youtube"
                                                placeholder="Enter Youtube URL"
                                                value="{{ old('youtube', $hardsellingFooter->youtube) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {{-- INSTAGRAM --}}
                                        <div class="mb-3">
                                            <label class="form-label">Instagram</label>
                                            <input type="text" class="form-control" name="instagram"
                                                placeholder="Enter Instagram URL"
                                                value="{{ old('instagram', $hardsellingFooter->instagram) }}" required>
                                        </div>
                                    </div>
                                </div>


                                <div class="row d-flex justify-content-center mb-3">
                                    <div class="col-md-6">
                                        {{-- YOUTUBE --}}
                                        <div class="mb-3">
                                            <label class="form-label">Tiktok Link</label>
                                            <input type="text" class="form-control" name="tiktok"
                                                placeholder="Enter Tiktok URL"
                                                value="{{ old('tiktok', $hardsellingFooter->tiktok) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {{-- INSTAGRAM --}}
                                        <div class="mb-3">
                                            <label class="form-label">Facebook</label>
                                            <input type="text" class="form-control" name="facebook"
                                                placeholder="Enter Facebook URL"
                                                value="{{ old('facebook', $hardsellingFooter->facebook) }}" required>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <button class="btn btn-primary mt-3">
                            Save Hardselling Footer
                        </button>

                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
