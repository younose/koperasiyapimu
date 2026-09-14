<?php
namespace App\Support;
class Icons {
  public static function get($n){
    $p=[
     'home'=>'<path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/>',
     'users'=>'<circle cx="9" cy="8" r="3.2"/><path d="M3.5 20a5.5 5.5 0 0 1 11 0"/><path d="M16 5.5a3 3 0 0 1 0 5.6"/><path d="M17 20a5 5 0 0 0-3-4.5"/>',
     'piggy'=>'<path d="M4 12a6 6 0 0 1 6-6h4a6 6 0 0 1 6 6v3h-2v3h-3v-2H9v2H6v-3H4z"/><circle cx="15" cy="11" r="1"/>',
     'book'=>'<path d="M5 4h12a2 2 0 0 1 2 2v14H7a2 2 0 0 1-2-2z"/><path d="M5 18a2 2 0 0 1 2-2h12"/>',
     'loan'=>'<circle cx="12" cy="12" r="9"/><path d="M12 7v10M9.5 9.5c0-1.2 1.1-2 2.5-2s2.5.8 2.5 2-1.1 1.8-2.5 2-2.5.8-2.5 2 1.1 2 2.5 2 2.5-.8 2.5-2"/>',
     'shu'=>'<path d="M4 19V9M10 19V5M16 19v-7M22 19H2"/>',
     'bell'=>'<path d="M6 9a6 6 0 0 1 12 0c0 5 2 6 2 6H4s2-1 2-6"/><path d="M10 19a2 2 0 0 0 4 0"/>',
     'gear'=>'<circle cx="12" cy="12" r="3.2"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M5 5l2 2M17 17l2 2M19 5l-2 2M7 17l-2 2"/>',
     'plus'=>'<path d="M12 5v14M5 12h14"/>','pencil'=>'<path d="M4 20h4L18 10l-4-4L4 16z"/><path d="M13 5l4 4"/>',
     'trash'=>'<path d="M4 7h16M9 7V5h6v2M6 7l1 13h10l1-13"/>','search'=>'<circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4"/>',
     'in'=>'<circle cx="12" cy="12" r="9"/><path d="M12 8v8M8 12l4 4 4-4"/>','out'=>'<circle cx="12" cy="12" r="9"/><path d="M12 16V8M8 12l4-4 4 4"/>',
     'wallet'=>'<path d="M3 7a2 2 0 0 1 2-2h12v4"/><path d="M3 7v10a2 2 0 0 0 2 2h15V9H5a2 2 0 0 1-2-2"/><circle cx="16" cy="14" r="1"/>',
     'lock'=>'<rect x="5" y="10" width="14" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>',
     'key'=>'<circle cx="8" cy="12" r="3.5"/><path d="M11.5 12H21l-2 2 2 2M15 12v3"/>',
     'user'=>'<circle cx="12" cy="8" r="4"/><path d="M4 20a8 8 0 0 1 16 0"/>','logout'=>'<path d="M14 4h4a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-4"/><path d="M9 12h11M16 8l4 4-4 4"/>',
     'check'=>'<path d="M20 6 9 17l-5-5"/>','info'=>'<circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8h.01"/>',
     'x'=>'<path d="M6 6l12 12M18 6L6 18"/>',
     'doc'=>'<path d="M7 3h7l5 5v13H7z"/><path d="M14 3v5h5"/><path d="M10 13h6M10 17h6"/>',
     'card'=>'<rect x="3" y="6" width="18" height="12" rx="2"/><path d="M3 10h18M7 15h4"/>',
     'print'=>'<path d="M7 9V3h10v6"/><rect x="5" y="9" width="14" height="7" rx="1"/><path d="M8 16h8v5H8z"/>',
    ];
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">'.($p[$n]??'').'</svg>';
  }
}
