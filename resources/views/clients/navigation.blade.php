{{-- Toast de bienvenue --}}
<style>
    #welcomeToast {
        min-width: 320px;
        font-size: 1.2rem;
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.25);
        cursor: default;
    }
    #welcomeToast .toast-body {
        font-weight: 600;
    }

    /* Style du chat modal */
    #chatModal .modal-dialog {
        max-width: 400px;
    }
    #chatModal .modal-content {
        border-radius: 1rem;
        overflow: hidden;
    }
    #chatMessages {
        height: 350px;
        overflow-y: auto;
        padding: 15px;
        background-color: #f8f9fa;
    }
    .message {
        padding: 10px;
        border-radius: 12px;
        margin-bottom: 10px;
        max-width: 85%;
        word-wrap: break-word;
    }
    .user-message {
        background-color: #d1e7dd;
        align-self: flex-end;
        text-align: right;
    }
    .ai-message {
        background-color: #e2e3e5;
        align-self: flex-start;
    }
    #chatForm input {
        border-radius: 50px;
    }
    #chatForm button {
        border-radius: 50px;
    }
</style>

<nav class="nav nav-pills nav-fill mb-5 shadow-sm rounded-4 overflow-hidden">
    <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ url('/clients/dashboard') }}">
        <i class="icon-home"></i> Dashboard
    </a>
    <a class="nav-link" href="{{ url('/clients/MesServices/' . $client['ID_']) }}">
        <i class="icon-briefcase"></i> Mes Abonnements
    </a>
    <a class="nav-link" href="{{ url('/clients/MesContrat/' . $client['ID_']) }}">
        <i class="icon-file-text"></i> Mes Contrats
    </a>
    <a class="nav-link" href="{{ url('/clients/MesTransact/' . $client['ID_']) }}">
        <i class="icon-wallet"></i> Mes Transactions
    </a>
    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#chatModal">
                    <i></i> Service Client
                </a>
<a class="nav-link" href="{{ url('/clients/MonProfil')  }}">
            <i class="icon-user"></i> Mon Profil
    </a>
</nav>
<div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="chatModalLabel">Subsure - Assistant Client</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body d-flex flex-column" id="chatMessages">
                <div class="message ai-message mb-2">
                    Bonjour <strong>{{ $client['nom'] ?? 'Utilisateur' }}</strong> ! 👋<br>
                    En quoi puis-je vous être utile aujourd'hui ?
                </div>
            </div>
            <div class="modal-footer p-2 bg-light">
                <form id="chatForm" class="d-flex w-100" method="POST" action="{{ route('generate') }}">
                    @csrf
                    <input type="text" id="chatInput" name="prompt" class="form-control me-2" placeholder="Posez votre question..." required>
                    <button type="submit" class="btn btn-primary">Envoyer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script chat -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chatForm = document.getElementById('chatForm');
    const chatMessages = document.getElementById('chatMessages');
    const chatInput = document.getElementById('chatInput');

    chatForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const userMessage = chatInput.value.trim();
        if (!userMessage) return;

        // Ajouter message utilisateur
        const userDiv = document.createElement('div');
        userDiv.className = 'message user-message mb-2';
        userDiv.innerHTML = `<strong>Vous :</strong> ${userMessage}`;
        chatMessages.appendChild(userDiv);

        chatMessages.scrollTop = chatMessages.scrollHeight;

        // Envoyer au serveur via fetch
        fetch("{{ route('generate') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ prompt: userMessage })
        })
        .then(res => res.json())
        .then(data => {
            const aiDiv = document.createElement('div');
            aiDiv.className = 'message ai-message mb-2';
            aiDiv.innerHTML = `<strong>Subsure :</strong> ${data.response}`;
            chatMessages.appendChild(aiDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        })
        .catch(err => console.error(err));

        chatInput.value = '';
    });
});
</script>