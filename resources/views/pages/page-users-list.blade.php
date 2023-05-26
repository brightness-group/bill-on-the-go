@extends('layouts.contentLayoutMaster')
 page title
@section('title','Users List')
 vendor styles
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
@endsection
 page styles
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-users.css')}}">
@endsection

@section('content')
<!-- users list start -->
<section class="users-list-wrapper">
  <div class="users-list-filter px-1">
    <form>
      <div class="row border rounded py-2 mb-2">
        <div class="col-12 col-sm-6 col-lg-3">
          <label for="users-list-verified">Verified</label>
          <fieldset class="form-group">
            <select class="form-control" id="users-list-verified">
                <option value="">Any</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
          </fieldset>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
          <label for="users-list-role">Role</label>
          <fieldset class="form-group">
            <select class="form-control" id="users-list-role">
              <option value="">Any</option>
              <option value="User">User</option>
              <option value="Staff">Staff</option>
            </select>
          </fieldset>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
          <label for="users-list-status">Status</label>
          <fieldset class="form-group">
            <select class="form-control" id="users-list-status">
              <option value="">Any</option>
              <option value="Active">Active</option>
              <option value="Close">Close</option>
              <option value="Banned">Banned</option>
            </select>
          </fieldset>
        </div>
        <div class="col-12 col-sm-6 col-lg-3 d-flex align-items-center">
          <button type="reset" class="btn btn-primary btn-block glow users-list-clear mb-0">Clear</button>
        </div>
      </div>
    </form>
  </div>
  <div class="users-list-table">
    <div class="card">
      <div class="card-content">
        <div class="card-body">
          <!-- datatable start -->
          <div class="table-responsive">
            <table id="users-list-datatable" class="table">
              <thead>
                <tr>
                    <th>id</th>
                    <th>name</th>
                    <th>email</th>
                    <th>created at</th>
                    <th>two factor secret</th>
                    <th>role</th>
                    <th>status</th>
                    <th colspan="3">Actions</th>
                </tr>
              </thead>
              <tbody>
              @if( count($users) )
                @foreach($users as $user)
                <tr>
                  <td>{{ $user->id }}</td>
                  <td>{{ $user->name }}</td>
                  <td>{{ $user->email }}</td>
                  <td>{{ $user->created_at }}</td>
                  <td>{{ $user->two_factor_secret }}</td>
                  <td>Staff</td>
                  <td><span class="badge badge-light-success">Active</span></td>
                  <td>
                    <div class="display-inline-block">
                      <button wire:click="selectItem({{ $user->id }}, 'update')" class="btn btn-icon" data-toggle="modal" data-placement="top" title="Edit">
                        <i class="bx bx-edit"></i>
                      </button>
                      <button wire:click="selectItem({{ $user->id }}, 'delete')" class="btn btn-icon" data-toggle="modal" data-placement="top" title="Delete">
                        <i class="bx bx-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
                @endforeach
              @endif
              </tbody>
            </table>
          </div>
          <!-- datatable ends -->
        </div>
      </div>
    </div>
  </div>
</section>
<!-- users list ends -->
@endsection

 vendor scripts
@section('vendor-scripts')
<script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
@endsection

 page scripts
@section('page-scripts')
<script src="{{asset('js/scripts/pages/page-users.js')}}"></script>
@endsection
