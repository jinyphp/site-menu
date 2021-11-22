<x-theme theme="admin.sidebar2">
    <x-theme-layout>

        <!-- start page title -->
        <x-row >
            <x-col class="col-8">
                <div class="page-title-box">
                    <ol class="m-0 breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Site</a></li>
                        <li class="breadcrumb-item active">Menu</li>
                    </ol>

                    <div class="mb-3">
                        <h1 class="align-middle h3 d-inline"><strong>사이트</strong> 메뉴 코드</h1>
                        <p></p>
                    </div>
                </div>
            </x-col>
        </x-row>
        <!-- end page title -->

        <x-row>
            <div class="col-lg-2">
                @include("jinyadmin::users.submenu")
            </div>
            <div class="col-lg-10">
                <x-card>
                    <x-card-header>

                    </x-card-header>
                    <x-card-body>

                        @livewire('Admin-SiteMenu-Code')

                    </x-card-body>
                </x-card>
            </div>
        </x-row>


    </x-theme-layout>
</x-theme>

