@extends('layouts.app')

@section('content')
@if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
@if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
@endif

<div class="container">
    <h1>Frequently Asked Questions</h1>


    @can('admin', Auth::user())
        <div class="mb-3">
            <a href="{{ route('faq.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add FAQ
            </a>
        </div>
    @endcan

    <div class="faq-section">
        @foreach ($faqs as $faq)
            <div class="faq-item">
                <h3>{{ $faq->question }}</h3>
                <p>{!! $faq->answer !!}</p>
                @can('admin', Auth::user())
                    <div class="faq-actions">
                        <a class="button" href="{{ route('faq.edit', $faq->id) }}" id="btn-edit" title="Edit">
                            <i class="fas fa-pencil"></i> 
                        </a>
                        <form action="{{ route('faq.destroy', $faq->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this FAQ?')" title="Delete">
                                <i class="fas fa-trash-alt"></i> 
                            </button>
                        </form>
                    </div>
                @endcan
            </div>
        @endforeach
    </div>

    <div class="pagination">
        {{ $faqs->links() }}
    </div>
@endsection