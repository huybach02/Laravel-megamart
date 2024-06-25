@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Tin Nhắn</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Tin Nhắn</a></div>
            </div>
        </div>

        <div class="section-body">

            <div class="row align-items-center justify-content-center">
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card" style="height: 70vh">
                        <div class="card-header">
                            <h4>Danh sách khách hàng</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled list-unstyled-border">

                                @foreach ($chatUsers as $chatUser)
                                    @php
                                        $unseenMessages = \App\Models\Chat::where([
                                            'sender_id' => $chatUser->senderProfile->id,
                                            'receiver_id' => auth()->user()->id,
                                            'seen' => 0,
                                        ])->exists();
                                    @endphp

                                    <li class="d-flex align-items-center chat-user-profile"
                                        data-id="{{ $chatUser->senderProfile->id }}">
                                        <img alt="image" class="mr-3 rounded-circle" width="50"
                                            src="{{ asset($chatUser->senderProfile->image) }}">

                                        @if ($unseenMessages)
                                            <div class="notify">
                                                <div class="text-danger text-small font-600-bold mr-2"><i
                                                        class="fas fa-circle"></i>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="font-weight-bold profile-name">{{ $chatUser->senderProfile->name }}
                                        </div>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-8">
                    <div class="card chat-box" id="mychatbox" style="height: 70vh">
                        <div class="card-header title">
                            {{-- <h4>Chat with Rizal</h4> --}}
                        </div>
                        <div class="card-body chat-content" data-inbox="">
                            {{-- <div class="chat-item chat-left">
                                <img src="" alt="">
                                <div class="chat-details">
                                    <div class="chat-text">Hello</div>
                                    <div class="chat-time">10:20</div>
                                </div>
                            </div>
                            <div class="chat-item chat-right">
                                <img src="" alt="">
                                <div class="chat-details">
                                    <div class="chat-text">Hello</div>
                                    <div class="chat-time">10:20</div>
                                </div>
                            </div> --}}
                        </div>
                        <div class="card-footer chat-form" style="display: none;">
                            <form id="message-form">
                                <input type="text" class="form-control message-box" placeholder="Nhập tin nhắn"
                                    name="message">
                                <input type="hidden" name="receiver_id" value="" id="receiver_id">
                                <button class="btn btn-primary">
                                    <i class="far fa-paper-plane"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        const mainChatBox = $(".chat-content")

        function formatDatetime(datetimeString) {
            const options = {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };

            const date = new Date(datetimeString);
            return date.toLocaleString('en-GB', options).replace(',', '');
        }

        function scrollToBottom() {
            mainChatBox[0].scrollTop = mainChatBox[0].scrollHeight;
        }

        $(document).ready(function() {
            // $(".chat-form").on("click", function() {
            //     let $span = $(".chat-user-profile").find(".notify");

            //     // Nếu thẻ span đã tồn tại thì xóa, nếu không thì thêm mới
            //     if ($span.length > 0) {
            //         $span.remove();
            //     }
            // })

            $(".chat-user-profile").on("click", function() {
                $(".card-footer.chat-form").css("display", "block");

                let receiverId = $(this).data("id")

                let receiverImage = $(this).find("img").attr("src");

                let receiverName = $(this).find(".profile-name").text();

                mainChatBox.attr("data-inbox", receiverId)

                $(".title").html(`<h4>Nhắn tin với ${receiverName}</h4>`);

                $("#receiver_id").val(receiverId)

                let $span = $(this).find(".notify");

                // Nếu thẻ span đã tồn tại thì xóa, nếu không thì thêm mới
                if ($span.length > 0) {
                    $span.remove();
                }

                $.ajax({
                    url: "{{ route('admin.get-messages') }}",
                    method: "GET",
                    data: {
                        receiverId: receiverId
                    },
                    beforeSend: function() {
                        mainChatBox.html("")
                    },
                    success: function(data) {
                        console.log(data);

                        // let title = `<h2>Nhắn tin với ${data.vendorName}</h2>`
                        // $(".wsus__chat_area").html(title)
                        $.each(data, function(index, value) {
                            let message = ""
                            if (value.sender_id == USER.id) {
                                message = `
                                  <div class="chat-item chat-right">
                                      <img src="${USER.image}" alt="">
                                      <div class="chat-details">
                                          <div class="chat-text">${value.message}</div>
                                          <div class="chat-time">${formatDatetime(value.created_at)}</div>
                                      </div>
                                  </div>
                                  `
                            } else {
                                message = `
                                  <div class="chat-item chat-left">
                                      <img src="${receiverImage}" alt="">
                                      <div class="chat-details">
                                          <div class="chat-text">${value.message}</div>
                                          <div class="chat-time">${formatDatetime(value.created_at)}</div>
                                      </div>
                                  </div>
                                  `
                            }

                            mainChatBox.append(message)
                        })

                        scrollToBottom()

                    },
                    error: function(error) {
                        console.log(error)
                    },
                    complete: function() {

                    }
                })
            })

            $("#message-form").on("submit", function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                let messageData = $(".message-box").val();

                var formSubmitting = false;

                if (formSubmitting || messageData === "") {
                    return
                }
                let message = `
                    <div class="chat-item chat-right">
                        <img src="${USER.image}" alt="">
                        <div class="chat-details">
                            <div class="chat-text">${messageData}</div>
                            <div class="chat-time"></div>
                        </div>
                    </div>
                    `

                mainChatBox.append(message)
                scrollToBottom()

                $.ajax({
                    method: "POST",
                    url: "{{ route('admin.send-message') }}",
                    data: formData,
                    beforeSend: function() {
                        $(".send-button").prop("disabled", true)
                        $(".message-box").prop("disabled", true)
                        formSubmitting = true
                    },
                    success: function(data) {
                        $(".message-box").val("")
                    },
                    error: function(data) {
                        toastr.error(data.responseJSON.message)
                        $(".send-button").prop("disabled", false)
                        $(".message-box").prop("disabled", false)
                        formSubmitting = false
                    },
                    complete: function(data) {
                        $(".send-button").prop("disabled", false)
                        $(".message-box").prop("disabled", false)
                        formSubmitting = false
                    }
                })
            })
        });
    </script>
@endpush
