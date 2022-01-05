<div>
    <x-loading-indicator/>

    {{-- 파일업로드 --}}
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{session('message')}}
        </div>
    @endif

    <x-flex-between>
        <div>
            <x-button primary wire:click="export">
                Json 다운로드
            </x-button>

            <x-button primary wire:click="$emit('encodeToJson')">
                Json 변환
            </x-button>

        </div>
        <div>
            <form wire:submit.prevent="fileUpload" id="form-upload" enctype="multipart/form-data">
                <x-flex-between>
                    <div>
                        <input type="file" name="filename" wire:model="filename" class="form-control"/>
                        @error('filename') <span class="text-danger">{{$message}}</span> @enderror
                    </div>
                    <div class="pl-2">
                        <button type="submit" class="btn btn-success flot-right">Json Upload</button>
                    </div>
                </x-flex-between>
            </form>
        </div>
    </x-flex-between>

</div>
