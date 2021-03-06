@extends('layouts.app')

@section('content')
<b-container fluid>
    <b-row>
        <b-col lg="3" md="12" sm="12">
            <graduando               
                cui_year = "{{ substr(Auth::user()->administrado->cui, 0, 4) }}" 
                cui = "{{ Auth::user()->administrado->cui }}"                                 
            />            
        </b-col>
        <b-col lg="9" md="12" sm="12">
            <transition>
                <router-view :key="$route.fullPath"></router-view>
            </transition>
        </b-col>
    </b-row>
</b-container>
@endsection