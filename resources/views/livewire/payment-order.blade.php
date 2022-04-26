<div>
    <div class="grid grid-cols-5 gap-6 py-8 container-menu">
        <div class="col-span-2">
            <div class="p-6 px-6 pt-6 bg-white rounded-lg shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <img class="h-8" src="{{ asset('img/MC_VI_DI_2-1.jpg') }}" alt="">
                    <div class="text-gray-700">
                        <p class="text-sm font-semibold">
                            Subtotal: {{ $order->total - $order->shipping_cost }} &euro;
                        </p>
                        <p class="text-sm font-semibold">
                            Envío: {{ $order->shipping_cost }} &euro;
                        </p>
                        <p class="text-lg font-semibold uppercase">
                            Pago: {{ $order->total }} &euro;
                        </p>
                    </div>
                </div>
                <div id="paypal-button-container"></div>
            </div>
        </div>
        @push('scripts')
            <script src="https://www.paypal.com/sdk/js?client-id={{ config('services.paypal.client_id') }}&currency=EUR">
            </script>
            <script>
                paypal.Buttons({
                    createOrder: function(data, actions) {
                        return actions.order.create({
                            purchase_units: [{
                                amount: {
                                    value: "{{ $order->total }}"
                                }
                            }]
                        });
                    },
                    onApprove: function(data, actions) {
                        return actions.order.capture().then(function(orderData) {
                            Livewire.emit('payOrder');
                        });
                    }
                }).render('#paypal-button-container');
            </script>
        @endpush
    </div>
