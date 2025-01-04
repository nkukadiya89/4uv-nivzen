@extends('backend.page')

@section('title', 'Edulake - Add Tax')

@section('content_header')
    <h1 class="m-0 text-dark">Add User</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
            @endif
            <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ route('admin.user.edit', $user->id) }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="card-body">
                        {{-- status --}}
                             <div class="form-group">
                                <label for="country_id">Status</label>
                                <select id="status" class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" required>
                                  <option value="1" @if($user->status == 1) selected @endif>Active</option>
                                  <option value="0" @if($user->status == 0) selected @endif>Inactive</option>
                                </select>
                                @if ($errors->has('status'))
                                    <span class="error invalid-feedback">{{ $errors->first('status') }}</span>
                                @endif
                            </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn bg-{{ config('adminlte.skin', 'blue') }}">
                            Save
                        </button>
                    </div>
                    <!-- /.card-footer -->
                </form>
            </div>
        </div>
    </div>
@stop

