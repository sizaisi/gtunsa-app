@extends('layouts.app')

@section('content')
<b-container fluid>
    <b-row>
        <b-col cols="3">
            <graduando-component></graduando-component>
        </b-col>
        <b-col cols="9">
            <transition>
                <router-view></router-view>
            </transition>
        </b-col>
    </b-row>
</b-container>
@endsection