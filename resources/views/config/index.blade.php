@extends('layouts.template')

@section('content')
    <div class="row g-3 mb-4 align-items-center justify-content-between">
        <div class="col-auto">
            <h1 class="app-page-title mb-0">Configurations</h1>
        </div>
        <div class="col-auto">
            <div class="page-utilities">
                <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                    <div class="col-auto">
                        <form class="table-search-form row gx-1 align-items-center">
                            <div class="col-auto">
                                <input type="text" id="search-orders" name="searchorders"
                                    class="form-control search-orders" placeholder="Search">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn app-btn-secondary">Search</button>
                            </div>
                        </form>

                    </div>

                    <div class="col-auto">
                        <a class="btn app-btn-secondary" href="{{ route('configuration.create') }}">
                            <i class="fas fa-add"></i>
                            Nouvelle configuration
                        </a>
                    </div>
                </div>
                <!--//row-->
            </div>
            <!--//table-utilities-->
        </div>
        <!--//col-auto-->
    </div>
    <!--//row-->


    @if (Session::get('success_message'))
        <div class="alert alert-success">{{ Session::get('success_message') }}</div>
    @endif


    <div class="app-card app-card-orders-table shadow-sm mb-5">
        <div class="app-card-body">
            <div class="table-responsive">
                <table class="table app-table-hover mb-0 text-left">
                    <thead>
                        <tr>
                            <th class="cell">#</th>
                            <th class="cell">Type</th>
                            <th class="cell">Valeur</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                        @php
                            $ids = 0
                        @endphp

                        @forelse ($configurations as $config)
                            <tr>
                                <td class="cell">{{ $ids += 1 }}</td>
                                <td class="cell"><span class="truncate">
                                        @if ($config->type === 'PAYEMENT_DATE')
                                            Date mensuel de paiement
                                        @endif

                                        @if ($config->type === 'APP_NAME')
                                            Nom de l'application
                                        @endif

                                        @if ($config->type === 'DEVELOPPER_NAME')
                                            Equipe de développement
                                        @endif

                                </td>
                                <td class="cell"><span class="truncate">{{ $config->value }}

                                        @if ($config->type === 'PAYEMENT_DATE')
                                            de chaque mois
                                        @endif

                                </td>
                                <td class="cell">

                                    <a class="btn-sm app-btn-secondary"
                                        href="{{ route('configuration.delete', $config) }}">Supprimer</a>
                                </td>

                            </tr>
                        @empty

                            <tr>
                                <td class="cell" colspan="4">Aucune configuration enregistrer</td>

                            </tr>
                        @endforelse



                    </tbody>
                </table>
            </div>
            <!--//table-responsive-->

        </div>
        <!--//app-card-body-->
    </div>
    <!--//app-card-->
    <nav class="app-pagination">
        {{ $configurations->links() }}
    </nav>
    <!--//app-pagination-->
    <!--//tab-content-->
@endsection
