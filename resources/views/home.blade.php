@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6 mb-3">
            <div class="card p-2">
                <h3 class="text-center">Everyone</h3>
                <div id="global" class="bg-light p-2" style="max-height: 500px; overflow-x: auto;"></div>
                <hr>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Message" id="global-message">
                    <div class="input-group-append">
                        <button id="btn-global" class="btn btn-success d-block" data-url="{{route('global-message')}}">Send</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card p-3">
                <h3 class="text-center">Private Message</h3>
                <div id="private" class="bg-light p-2" style="max-height: 500px; overflow-x: auto;"></div>
                <hr>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="User ID" id="private-id">
                    <input type="text" class="form-control" placeholder="Message" id="private-message">
                    <div class="input-group-append">
                        <button id="btn-private" class="btn btn-warning d-block" data-url="{{route('private-message')}}">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
$(document).ready(function() {
    window.Echo.channel('EveryoneChannel')
       .listen('.EveryoneMessage', function (e) {
            console.log('EveryoneMessage',e)
            let className = e.from == "{{auth()->user()->name}}" ? 'alert-success float-right' : 'alert-warning float-left'
            $('#global').append(
                '<div class="alert '+className+' p-2 w-75">'+
                    '<p class="small">From: '+e.from+'</p>'+
                    '<p>'+e.message+'</p>'+
                '</div>'
            )
            e.from != "{{auth()->user()->name}}" ? playSound() : ''
        })

    @if(auth()->check())
        window.Echo.private("user-{{auth()->user()->id}}")
            .listen('.PrivateMessage', (e) => {
                console.log('PrivateMessage',e)
                let className = e.from == "{{auth()->user()->name}}" ? 'alert-success float-right' : 'alert-warning float-left'
                $('#private').append(
                    '<div class="alert '+className+' p-2 w-75">'+
                        '<p class="small">From: '+e.from+'</p>'+
                        '<p>'+e.message+'</p>'+
                    '</div>'
                )
                playSound()
            })
    @endif
})

function playSound() {
    const audio = new Audio("{{asset('juntos-607.mp3')}}")
    audio.play()
}

$(document).on('click', '#btn-global', function() {
    $.ajax({
        url: $(this).attr('data-url'),
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            message: $('#global-message').val()
        },
        success: function(res) {
            console.log('res',res)
            $('#global-message').val('')
        },
        error: function(err) {
            console.log('err',err)
        }
    })
})

$(document).on('click', '#btn-private', function() {
    $.ajax({
        url: $(this).attr('data-url'),
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            id: $('#private-id').val(),
            message: $('#private-message').val()
        },
        success: function(res) {
            console.log('res',res)
            $('#private-id').val('')
            $('#private-message').val('')
        },
        error: function(err) {
            console.log('err',err)
        }
    })
    ownerChat()
})

function ownerChat() {
    $('#private').append(
        '<div class="alert alert-success float-right p-2 w-75">'+
            '<p class="small">From: {{auth()->user()->name}}</p>'+
            '<p>'+$('#private-message').val()+'</p>'+
        '</div>'
    )
}
</script>
@endpush