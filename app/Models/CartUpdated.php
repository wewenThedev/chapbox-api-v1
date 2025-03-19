/*🔗 WebSockets avec Laravel Echo + Pusher
1️⃣ Côté Laravel, ajoutez un événement de mise à jour du panier :*/

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class CartUpdated implements ShouldBroadcast {
    use InteractsWithSockets, SerializesModels;

    public $cart;

    public function __construct($cart) {
        $this->cart = $cart;
    }

    public function broadcastOn() {
        return ['cart-channel'];
    }
}
