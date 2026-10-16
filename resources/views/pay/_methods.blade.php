{{-- Payment method cards shared by the Make a Payment and Donate pages.
     Expects $verb ('Pay' / 'Donate') and the three descriptions in $copy.
     Card and mobile money open the Swish payment modal (pay._swish_modal). --}}
<div class="cards-grid pay-methods">
    <div class="card">
        <div class="card-icon"><i class="fa fa-credit-card"></i></div>
        <h3>{{ $verb === 'Pay' ? 'Card Payments' : 'Card Payment' }}</h3>
        <p>{{ $copy['card'] }}</p>
        <button type="button" class="cta-button pay-link" data-swish-open="card"><i class="fa fa-credit-card me-2"></i>{{ $verb }} with Card</button>
    </div>
    <div class="card">
        <div class="card-icon"><i class="fa fa-mobile-screen-button"></i></div>
        <h3>Mobile Money</h3>
        <p>{{ $copy['mobile'] }}</p>
        <button type="button" class="cta-button pay-link" data-swish-open="mobile"><i class="fa fa-mobile-screen-button me-2"></i>{{ $verb === 'Pay' ? 'Pay with' : 'Donate via' }} Mobile Money</button>
    </div>
    <div class="card">
        <div class="card-icon"><i class="fa fa-building-columns"></i></div>
        <h3>Online Banking</h3>
        <p>{{ $copy['bank'] }}</p>
        <a href="{{ route('apply.payment') }}#bank-deposit" class="cta-button-outline pay-link"><i class="fa fa-building-columns me-2"></i>Bank Transfer Details</a>
    </div>
</div>
@include('pay._swish_modal', ['category' => $verb === 'Pay' ? 'Payment' : 'Donation'])
