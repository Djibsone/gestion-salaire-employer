@extends('layouts.template')

@section('content')
    <div class="row g-3 mb-4 align-items-center justify-content-between">
        <div class="col-auto">
            <h1 class="app-page-title mb-0">Employers</h1>
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
                    <!--//col-->
                    <div class="col-auto">

                        <select class="form-select w-auto">
                            <option selected value="option-1">All</option>
                            <option value="option-2">This week</option>
                            <option value="option-3">This month</option>
                            <option value="option-4">Last 3 months</option>

                        </select>
                    </div>
                    <div class="col-auto">
                        <a class="btn app-btn-secondary" href="{{ route('employer.create') }}">
                            <i class="fas fa-plus"></i>
                            Ajouter Employer
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



    <div class="tab-content" id="orders-table-tab-content">
        <div class="tab-pane fade show active" id="orders-all" role="tabpanel" aria-labelledby="orders-all-tab">
            <div class="app-card app-card-orders-table shadow-sm mb-5">
                <div class="app-card-body">
                    <div class="table-responsive">
                        <table class="table app-table-hover mb-0 text-left">
                            <thead>
                                <tr>
                                    <th class="cell">#</th>
                                    <th>Departement</th>
                                    <th class="cell">Nom</th>
                                    <th class="cell">Prenom</th>
                                    <th class="cell">Email</th>
                                    <th class="cell">Contact</th>
                                    <th>Salaire</th>
                                    <th class="cell"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $ids = 0
                                @endphp

                                @forelse ($employers as $employer)
                                    <tr>
                                        <td class="cell">{{ $ids += 1 }}</td>

                                        <td>{{ $employer->departement?->name }}</td>
                                        <td class="cell"><span class="truncate">{{ $employer->lastname }}</td>
                                        <td class="cell">{{ $employer->firstname }}</td>
                                        <td class="cell">{{ $employer->email }}</td>
                                        <td class="cell">{{ $employer->contact }}
                                        </td>

                                        <td class="cell"><span
                                                class="badge bg-success">{{ $employer->montant_journalier * 31 }}
                                                Euro</span>
                                        </td>

                                        <td class="cell">
                                            <a class="btn-sm app-btn-secondary"
                                                href="{{ route('employer.edit', $employer) }}">Editer</a>

                                            <a class="btn-sm app-btn-secondary"
                                                href="{{ route('employer.delete', $employer) }}">Supprimer</a>
                                        </td>
                                    </tr>
                                @empty

                                    <tr>
                                        <td class="cell" colspan="6">Aucun employer ajoutés</td>

                                    </tr>
                                @endforelse



                            </tbody>
                        </table>
                    </div>
                    <!--//table-responsive-->

                </div>
                <!--//app-card-body-->
            </div>

            <nav class="app-pagination">
                {{ $employers->links() }}
            </nav>
        </div>

        <!--//tab-pane-->
    </div>
    <!--//tab-content-->
@endsection
