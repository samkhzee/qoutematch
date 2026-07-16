@extends('Template::layouts.buyer_master')
@section('content')
    <div class="card custom--card">
        <div class="card-body">
            <form action="{{ route('buyer.ticket.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="register">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form--label d-block required">
                                    @lang('Priority')
                                </label>
                                <div class="support-priority-wrapper">
                                    <label class="support-priority" for="low">
                                        <span class="support-priority__title ">
                                            @lang('Low')
                                        </span>
                                        <input id="low" name="priority" type="radio" required value="1"
                                            @checked(@old('priority') == 1)>
                                        <span class="support-priority-circle">
                                            <svg class="check-circle" width="13" height="10" viewBox="0 0 13 10"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path class="check" d="M1 5L4.5 8.5L12.5 0.5" stroke="currentColor"
                                                    stroke-linecap="round" />
                                            </svg>
                                        </span>
                                    </label>
                                    <label class="support-priority" for="medium">
                                        <span class=" support-priority__title ">
                                            @lang('Medium')
                                        </span>
                                        <input id="medium" name="priority" type="radio" required value="2"
                                            {{ old('priority', 2) == 2 ? 'checked' : '' }}>
                                        <span class="support-priority-circle">
                                            <svg class="check-circle" width="13" height="10" viewBox="0 0 13 10"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path class="check" d="M1 5L4.5 8.5L12.5 0.5" stroke="currentColor"
                                                    stroke-linecap="round" />
                                            </svg>
                                        </span>
                                    </label>
                                    <label class="support-priority" for="high">
                                        <span class=" support-priority__title">
                                            @lang('High')
                                        </span>
                                        <span class="support-priority-circle">
                                            <svg class="check-circle" width="13" height="10" viewBox="0 0 13 10"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path class="check" d="M1 5L4.5 8.5L12.5 0.5" stroke="currentColor"
                                                    stroke-linecap="round" />
                                            </svg>
                                        </span>
                                        <input id="high" name="priority" required type="radio" value="3"
                                            @checked(@old('priority') == 3)>
                                    </label>
                                </div>
                                <small class="input-note-text style-two mt-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                    @lang('Please select the priority that best matches the severity of your issue').
                                </small>
                            </div>
                        </div>

                        <div class="col-md-12 form-group">
                            <label class="form--label required">@lang('Subject')</label>
                            <input class="form--control" name="subject" type="text" required
                                value="{{ @old('subject') }}">
                        </div>
                        <div class="col-md-12 form-group">
                            <label class="form--label required">@lang('Message')</label>
                            <textarea class="form--control" required name="message">{{ trim(@old('message')) }}</textarea>
                        </div>
                        <div class="col-md-12 form-group">
                            <label class="form--label">@lang('Attachments')</label>
                            <input class="form--control form-control custom--file-input" name="attachments[]" type="file"
                                multiple="" max="5" accept=".jpg, .jpeg, .png, .pdf, .doc, .docx">
                            <small class="input-note-text style-two mt-1">
                                <i class="fas fa-info-circle me-1"></i>
                                @lang('You can upload up to 5 files | Maximum upload size is ' . convertToReadableSize(ini_get('upload_max_filesize')) . ' | Allowed File Extensions: .jpg, .jpeg, .png, .pdf, .doc, .docx')</small>

                            <div class="attach-preview-wrapper input"></div>

                            <div class="col-md-12 text-end">
                                <button class="btn btn--base"><i class="las la-paper-plane"></i>
                                    @lang('Submit')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection


@push('script')
    <script>
        "use strict";
        (function($) {
            const fileInput = $(`[name="attachments[]"]`);
            let filesArray = [];

            fileInput.on('change', function() {
                $('.attach-preview-wrapper.input').empty();
                filesArray = Array.from(this.files);



                const fileSize = $(this).attr('max');
                if (filesArray.length > fileSize) {
                    this.value = '';
                    notify('error', `You cannot upload more than ${fileSize} files`);
                    return false;
                }

                filesArray.forEach((file, index) => {
                    const reader = new FileReader();
                    const fileExtension = file.name.split('.').pop().toLowerCase();

                    reader.onload = function(e) {
                        let imageUrl = e.target.result;
                        const nonImageExtensions = ['doc', 'docx', 'pdf', 'xls', 'xlsx', 'txt',
                            'ppt',
                            'pptx'
                        ];

                        if (!['jpg', 'jpeg', 'png'].includes(fileExtension)) {
                            imageUrl =
                                "{{ getImage(getFilePath('ticket') . '/' . 'doc_type.png') }}";
                        }

                        const html = `<div class="atach-preview" data-index="${index}">
                                    <div class="atach-preview__left">
                                        <div class="atach-preview__image">
                                            <img src="${imageUrl}" alt="${file.name}">
                                        </div>
                                        <div class="atach-preview__contemt">
                                            <p class="atach-preview__title">${file.name}</p>
                                            <p class="atach-preview__size">${getFileSize(file.size)}</p>
                                        </div>
                                    </div>
                                    <div class="atach-preview__action">
                                        <a href="javascript:void(0);" class="atach-icon delete-icon">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            `;

                        $('.attach-preview-wrapper.input').append(html);
                    };

                    reader.readAsDataURL(file);
                });

                $(document).on('click', '.delete-icon', function() {
                    const index = $(this).closest('.atach-preview').data('index');
                    filesArray.splice(index, 1);
                    const dataTransfer = new DataTransfer();
                    filesArray.forEach(file => dataTransfer.items.add(file));
                    fileInput[0].files = dataTransfer.files;
                    $(this).closest('.atach-preview').remove();
                });
            });

            function getFileSize(size) {
                if (size >= 1048576) {
                    return (size / 1048576).toFixed(2) + ' MB';
                } else if (size >= 1024) {
                    return (size / 1024).toFixed(2) + ' KB';
                } else {
                    return size + ' bytes';
                }
            }
        })(jQuery);
    </script>
@endpush
