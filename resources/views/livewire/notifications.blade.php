<div>
<div class="dropdown dropdown-inline" data-toggle="kt-tooltip" title="Notifikasi" data-placement="left">
    <a href="#" 
        class="btn btn-icon btn-{{ $unread>0?'danger':'primary'}}"
        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="flaticon2-notification"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-fit dropdown-menu-md dropdown-menu-right">
        <form>
            <!--begin: Head -->
            <div class="kt-head kt-head--skin-dark kt-head--fit-x kt-head--fit-b"
                style="background-image: url(assets/media/misc/bg-1.jpg)">
                <h3 class="kt-head__title">
                    Notifikasi
                </h3>
                <ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-3x nav-tabs-line-success kt-notification-item-padding-x"
                    role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active show" data-toggle="tab" href="#notifications"
                            role="tab" aria-selected="true" wire:ignore>{{ $unread }} belum dibaca</a>
                    </li>
                </ul>
            </div>

            <!--end: Head -->
            <div class="tab-content">
                <div class="tab-pane active show" id="notifications" role="tabpanel" wire:ignore.self>
                    <div>
                        @if($notifications->count()>0)
                        <div class="text-center p-2" width="100%">
                            <button class="btn btn-sm btn-outline text-warning" wire:click="markAsRead">Tandai semua sudah dibaca</button>
                        </div>
                        <div class="kt-notification kt-margin-t-10 kt-margin-b-10 kt-scroll" data-scroll="true"
                            data-height="300" data-mobile-height="200">
                            @foreach ($notifications as $notif)
                            <a href="{{ route('notification', $notif->id) }}" class="kt-notification__item">
                                <div class="kt-notification__item-icon">
                                    <i @class([
                                        "flaticon2-black-back-closed-envelope-shape"=>!$notif->read_at,
                                        "kt-font-danger"=>!$notif->read_at,
                                        "flaticon-envelope"=>$notif->read_at,
                                        "kt-font-success"=>$notif->read_at,
                                         ])></i>
                                </div>
                                <div class="kt-notification__item-details">
                                    <div class="kt-notification__item-title">
                                        {{ $notif->data['title'] }}
                                    </div>
                                    <div class="kt-notification__item-time">
                                        {{ Carbon\Carbon::parse($notif->data['created_at'])->diffForHumans() }}
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                        @else
                        <div class="kt-grid kt-grid--ver" style="min-height: 200px;">
                            <div class="kt-grid kt-grid--hor kt-grid__item kt-grid__item--fluid kt-grid__item--middle">
                                <div class="kt-grid__item kt-grid__item--middle kt-align-center">
                                    Tidak ada notifikasi baru
                                    <br/>untuk kamu
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>
