@extends('tenant.layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','TODO')
{{-- vendor styles --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/daterange/daterangepicker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/editors/quill/quill.snow.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/dragula.min.css')}}">
@endsection
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-todo.css')}}">
@endsection
{{-- sidebar --}}
@include('pages.app-todo-sidebar')
{{-- page content --}}
@section('content')
<div class="app-content-overlay"></div>
<div class="todo-app-area">
  <div class="todo-app-list-wrapper">
    <div class="todo-app-list">
      <div class="todo-fixed-search d-flex justify-content-between align-items-center">
        <div class="sidebar-toggle d-block d-lg-none">
          <i class="bx bx-menu"></i>
        </div>
        <fieldset class="form-group position-relative has-icon-left m-0 flex-grow-1">
          <input type="text" class="form-control todo-search" id="todo-search" placeholder="Search Task">
          <div class="form-control-position">
            <i class="bx bx-search"></i>
          </div>
        </fieldset>
        <div class="todo-sort dropdown mr-1">
          <button class="btn dropdown-toggle sorting" type="button" id="sortDropdown" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <i class="bx bx-filter"></i>
            <span>Sort</span>
          </button>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="sortDropdown">
            <a class="dropdown-item ascending" href="#">Ascending</a>
            <a class="dropdown-item descending" href="#">Descending</a>
          </div>
        </div>
      </div>
      <div class="todo-task-list list-group">
        <!-- task list start -->
        <ul class="todo-task-list-wrapper list-unstyled" id="todo-task-list-drag">
            <li class="todo-item" data-name="David Smith">
                <div class="todo-title-wrapper d-flex justify-content-sm-between justify-content-end align-items-center">
                    <div class="todo-title-area d-flex">
                        <i class='bx bx-grid-vertical handle'></i>
                        <div class="checkbox">
                            <input type="checkbox" class="checkbox-input" id="checkbox9">
                            <label for="checkbox9"></label>
                        </div>
                        <p class="todo-title mx-50 m-0 truncate">Hypnotherapy For Motivation Getting The Drive Back</p>
                    </div>
                    <div class="todo-item-action d-flex align-items-center">
                        <div class="todo-badge-wrapper d-flex"></div>
                        <span class=" badge badge-circle badge-light-primary">DS</span>
                        <a class='todo-item-favorite ml-75 warning'><i class="bx bx-star bxs-star"></i></a>
                        <a class='todo-item-delete ml-75'><i class="bx bx-trash"></i></a>
                    </div>
                </div>
            </li>
            <li class="todo-item" data-name="John Doe">
                <div class="todo-title-wrapper d-flex justify-content-sm-between justify-content-end align-items-center">
                    <div class="todo-title-area d-flex">
                        <i class='bx bx-grid-vertical handle'></i>
                        <div class="checkbox">
                            <input type="checkbox" class="checkbox-input" id="checkbox10">
                            <label for="checkbox10"></label>
                        </div>
                        <p class="todo-title mx-50 m-0 truncate">Fix Responsiveness</p>
                    </div>
                    <div class="todo-item-action d-flex align-items-center">
                        <div class="todo-badge-wrapper d-flex">
                            <span class="badge badge-light-warning badge-pill ml-50">Design</span>
                            <span class="badge badge-light-primary badge-pill ml-50">Frontend</span>
                            <span class="badge badge-light-secondary badge-pill ml-50" data-tag="ISSUE,BACKEND"
                                  data-toggle="tooltip" data-placement="bottom" title="ISSUE,BACKEND">
                    <i class='bx bx-dots-horizontal-rounded font-small-1'></i>
                  </span>
                        </div>
                        <div class="avatar ml-1">
                            <img src="{{asset('images/portrait/small/avatar-s-10.jpg')}}" alt="avatar" height="30"
                                 width="30">
                        </div>
                        <a class='todo-item-favorite ml-75'><i class="bx bx-star"></i></a>
                        <a class='todo-item-delete ml-75'><i class="bx bx-trash"></i></a>
                    </div>
                </div>
            </li>
        </ul>
        <!-- task list end -->
        <div class="no-results">
          <h5>No Items Found</h5>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('vendor-scripts')
    <script src="{{asset('vendors/js/pickers/daterange/moment.min.js')}}"></script>
    <script src="{{asset('vendors/js/pickers/daterange/daterangepicker.js')}}"></script>
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
    <script src="{{asset('vendors/js/editors/quill/quill.min.js')}}"></script>
    <script src="{{asset('vendors/js/extensions/dragula.min.js')}}"></script>
@endsection

{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pages/app-todo.js')}}"></script>
@endsection
