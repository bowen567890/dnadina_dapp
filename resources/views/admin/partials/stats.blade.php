<div class="row">
    <div class="col-md-2">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-line-chart"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">静态收益</span>
                <span class="info-box-number">{{ $stats['static_income_usdt'] ?? 0 }} USDT</span>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">直推收益</span>
                <span class="info-box-number">{{ $stats['total_tui_usdt'] ?? 0 }} USDT</span>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-sitemap"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">团队收益</span>
                <span class="info-box-number">{{ $stats['total_manage_usdt'] ?? 0 }} USDT</span>
            </div>
        </div>
    </div>
</div>
