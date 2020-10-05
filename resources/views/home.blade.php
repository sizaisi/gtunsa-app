@extends('layouts.app')

@section('content')
<b-container fluid>
    <b-row>
        <b-col lg="3" md="12" sm="12">
            <graduando-component></graduando-component>
        </b-col>
        <b-col lg="9" md="12" sm="12">
            <transition>
                <router-view></router-view>
            </transition>
        </b-col>
    </b-row>
</b-container>
@endsection