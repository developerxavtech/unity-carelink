<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">Billing & Payroll</h2>
                        <p class="text-muted">Financial management and reporting</p>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-outline-primary" disabled>
                            <i class="bi bi-download me-1"></i> Export
                        </button>
                        <button class="btn btn-primary" disabled>
                            <i class="bi bi-plus-circle me-1"></i> New Entry
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Summary -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Total Revenue</p>
                                <h3 class="mb-0 text-success">${{ number_format($totalRevenue, 2) }}</h3>
                            </div>
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-arrow-up-circle text-success fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Total Payroll</p>
                                <h3 class="mb-0 text-danger">${{ number_format($totalPayroll, 2) }}</h3>
                            </div>
                            <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-arrow-down-circle text-danger fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Net Income</p>
                                <h3 class="mb-0 {{ $netIncome >= 0 ? 'text-success' : 'text-danger' }}">
                                    ${{ number_format($netIncome, 2) }}
                                </h3>
                            </div>
                            <div class="bg-{{ $netIncome >= 0 ? 'success' : 'danger' }} bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-currency-dollar text-{{ $netIncome >= 0 ? 'success' : 'danger' }} fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Period Selector -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form method="GET" class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label small text-muted">Select Period</label>
                                <select name="period" class="form-select">
                                    <option value="current_month" {{ $period === 'current_month' ? 'selected' : '' }}>Current Month</option>
                                    <option value="last_month" {{ $period === 'last_month' ? 'selected' : '' }}>Last Month</option>
                                    <option value="quarter" {{ $period === 'quarter' ? 'selected' : '' }}>This Quarter</option>
                                    <option value="year" {{ $period === 'year' ? 'selected' : '' }}>This Year</option>
                                    <option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Custom Range</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Category</label>
                                <select name="category" class="form-select">
                                    <option value="all">All Transactions</option>
                                    <option value="billing">Billing Only</option>
                                    <option value="payroll">Payroll Only</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Program</label>
                                <select name="program" class="form-select">
                                    <option value="all">All Programs</option>
                                    <!-- Programs will be populated here -->
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-funnel"></i> Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs for Billing and Payroll -->
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs mb-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#billing" type="button">
                            <i class="bi bi-receipt"></i> Billing
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#payroll" type="button">
                            <i class="bi bi-cash-stack"></i> Payroll
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Billing Tab -->
                    <div class="tab-pane fade show active" id="billing">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0">
                                    <i class="bi bi-receipt text-success"></i> Billing Records
                                </h5>
                            </div>
                            <div class="card-body">
                                @forelse($billingRecords as $record)
                                    <div class="border-start border-3 border-success ps-3 mb-3 pb-3 border-bottom">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">{{ $record->description }}</h6>
                                                <small class="text-muted">{{ $record->date }}</small>
                                            </div>
                                            <h6 class="text-success mb-0">${{ number_format($record->amount, 2) }}</h6>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5">
                                        <i class="bi bi-receipt text-muted fs-1 mb-3"></i>
                                        <p class="text-muted">No billing records found.</p>
                                        <p class="small text-muted">Billing tracking functionality coming soon!</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Payroll Tab -->
                    <div class="tab-pane fade" id="payroll">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0">
                                    <i class="bi bi-cash-stack text-primary"></i> Payroll Records
                                </h5>
                            </div>
                            <div class="card-body">
                                @forelse($payrollRecords as $record)
                                    <div class="border-start border-3 border-primary ps-3 mb-3 pb-3 border-bottom">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">{{ $record->employee_name }}</h6>
                                                <small class="text-muted">{{ $record->period }} • {{ $record->hours }} hours</small>
                                            </div>
                                            <h6 class="text-primary mb-0">${{ number_format($record->amount, 2) }}</h6>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5">
                                        <i class="bi bi-cash-stack text-muted fs-1 mb-3"></i>
                                        <p class="text-muted">No payroll records found.</p>
                                        <p class="small text-muted">Payroll management functionality coming soon!</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
