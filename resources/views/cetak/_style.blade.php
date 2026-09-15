<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Plus Jakarta Sans',system-ui,Segoe UI,Arial,sans-serif;background:#eef1ed;color:#16231e;padding:24px}
.toolbar{max-width:720px;margin:0 auto 16px;display:flex;gap:10px;justify-content:flex-end}
.btn{display:inline-flex;align-items:center;gap:7px;border:1px solid #e6e9e5;background:#fff;color:#16231e;border-radius:11px;padding:10px 16px;font:inherit;font-weight:700;cursor:pointer;text-decoration:none}
.btn-primary{background:#0e6b56;border-color:#0e6b56;color:#fff}
.sheet{background:#fff;max-width:720px;margin:0 auto;padding:34px 38px;border-radius:6px;box-shadow:0 6px 24px rgba(0,0,0,.08)}
.rhead{display:flex;align-items:center;gap:16px;border-bottom:3px double #0e6b56;padding-bottom:16px;margin-bottom:20px}
.rhead img{width:64px;height:64px}
.rhead .knm{font-size:19px;font-weight:800;color:#0e6b56;line-height:1.2}
.rhead .ksub{font-size:12px;color:#5f7169;margin-top:3px}
.title{text-align:center;font-size:15px;font-weight:800;letter-spacing:2px;text-transform:uppercase;margin:6px 0 18px}
.meta{display:flex;justify-content:space-between;font-size:12.5px;color:#5f7169;margin-bottom:16px}
table.kv{width:100%;border-collapse:collapse;font-size:14px;margin-bottom:14px}
table.kv td{padding:9px 4px;border-bottom:1px solid #eef1ee;vertical-align:top}
table.kv td:first-child{color:#5f7169;width:170px}
.big{font-size:24px;font-weight:800;color:#0e6b56}
.terbilang{font-style:italic;background:#eef6f2;border:1px dashed #c8e2d7;border-radius:8px;padding:10px 14px;font-size:13px;margin:6px 0 18px}
.sign{display:flex;justify-content:space-between;margin-top:36px;font-size:13px;text-align:center}
.sign .box{width:44%}.sign .line{margin-top:60px;border-top:1px solid #16231e;padding-top:5px}
.note{font-size:11px;color:#8a978f;margin-top:22px;text-align:center}
.card{width:85.6mm;height:53.98mm;margin:16px auto;border-radius:3mm;overflow:hidden;border:1px solid #e6e9e5;box-shadow:0 10px 26px rgba(20,35,30,.14);display:flex;flex-direction:column}
.card .top{background:linear-gradient(120deg,#0a5344,#0e6b56);color:#fff;display:flex;align-items:center;gap:2.2mm;padding:2.6mm 3.2mm;flex-shrink:0}
.card .top img{width:8mm;height:8mm;background:#fff;border-radius:1.6mm;padding:.6mm;flex-shrink:0}
.card .top .n{font-size:2.7mm;font-weight:800;line-height:1.15}.card .top .s{font-size:1.7mm;letter-spacing:.3px;color:#bcd6c9;margin-top:.3mm}
.card .body{flex:1;display:flex;justify-content:space-between;gap:2mm;padding:2.4mm 3.2mm;background:#fff}
.card .body .f{margin-bottom:1.8mm}.card .body .f:last-child{margin-bottom:0}
.card .body .l{font-size:1.5mm;color:#5f7169;text-transform:uppercase;letter-spacing:.2px}
.card .body .v{font-size:2.2mm;font-weight:700;line-height:1.25;margin-top:.3mm}
.card .no{font-family:ui-monospace,monospace;font-size:2.7mm;font-weight:800;color:#0e6b56;letter-spacing:.3px;margin-top:.3mm}
.card .foot{background:#c1852a;color:#fff;font-size:1.5mm;text-align:center;padding:1.2mm;letter-spacing:.5px;text-transform:uppercase;flex-shrink:0}
.card-hint{max-width:85.6mm;margin:0 auto;text-align:center;font-size:11px;color:#8a978f}
@media print{ body{background:#fff;padding:0} .toolbar{display:none} .sheet{box-shadow:none;max-width:none;margin:0;border-radius:0} .card{border:1px solid #bbb;box-shadow:none} }
</style>
