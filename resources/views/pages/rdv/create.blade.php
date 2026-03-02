@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Nouveau rendez-vous" />

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
        <form method="POST" action="{{ route('rdvs.store') }}">
            @csrf
            @include('pages.rdv.form')

            <div class="mt-6">
                <button type="submit" class="inline-flex items-center rounded-md bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                    Enregistrer
                </button>
                <a href="{{ route('rdvs.index') }}" class="ml-4 text-sm text-gray-600 hover:underline">Annuler</a>
            </div>
        </form>
    </div>
@endsection
