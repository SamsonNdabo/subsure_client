@extends('Layouts.app')

@section('content')
    <div class="bg-dark position-relative mb-5" style="height: 380px; overflow: hidden;">
        <img src="{{ asset('assets/images/home/home_2.png') }}" alt="Service Image" class="w-100 h-100"
            style="object-fit: contain; background-color: #000;">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0, 0, 0, 0.4);"></div>
        <div class="position-absolute top-50 start-50 translate-middle text-center text-white px-3"
            style="text-shadow: 0 0 5px rgba(0,0,0,0.8); max-width: 90%;">
            <h1 class="display-4 fw-bold text-white">
                {{ $service['designation'] ?? $service[0]['designation'] ?? 'Service inconnu' }}
            </h1>
            <p class="fs-5 fst-italic text-white">Détails du service</p>
        </div>
    </div>

    <div class="container">
        {{-- Flash messages --}}
        @if(session('success'))
            <div class="alert alert-success rounded-2">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger rounded-2">{{ session('error') }}</div>
        @endif

        <div class="row gy-5">
            {{-- Colonne gauche : description + plans --}}
            <div class="col-lg-7">
                <section>
                    <h2 class="h4 text-primary mb-4">Description</h2>
                    <div class="bg-white p-4 rounded shadow-sm">
                        <p class="mb-0" style="line-height:1.6;">
                            {{ $service['description'] ?? $service[0]['description'] ?? 'Pas de description disponible.' }}
                        </p>
                    </div>
                </section>

                <section class="mt-5">
                    <h2 class="h4 text-primary mb-4">Plans disponibles</h2>
                    <div class="row g-4">
                        @foreach($plansForService as $plan)
                            @php $pid = $plan['id_plan'] ?? $plan['id']; @endphp
                            <div class="col-md-6">
                                <div class="card shadow-sm rounded-3 h-100">
                                    <div class="card-body d-flex flex-column h-100">
                                        <h5 class="card-title fw-bold text-primary">
                                            {{ $plan['designation'] ?? $plan['nom'] ?? 'Plan inconnu' }}
                                        </h5>
                                        <p class="text-muted small fst-italic mb-3">
                                            {{ $plan['description'] ?? 'Description non disponible.' }}
                                        </p>
                                        @if(isset($avantagesParPlan[$pid]) && count($avantagesParPlan[$pid]) > 0)
                                            <ul class="list-unstyled small mb-3 flex-grow-1">
                                                @foreach($avantagesParPlan[$pid] as $avantage)
                                                    <li>✅ {{ $avantage }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-muted fst-italic mb-3 flex-grow-1">Aucun avantage spécifié.</p>
                                        @endif
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fs-4 fw-semibold text-success">{{ number_format($plan['prix'], 2) }}
                                                $</span>
                                            <button class="btn btn-primary btn-sm rounded-pill" data-bs-toggle="modal"
                                                data-bs-target="#modal-abonnement" data-plan-id="{{ $pid }}"
                                                data-prix="{{ $plan['prix'] }}" data-interval="{{ $plan['intervalle'] ?? 30 }}">
                                                S'abonner
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>

            {{-- Colonne droite : entreprise + services liés --}}
            <div class="col-lg-5">
                @if($entreprise)
                    <section class="mb-5">
                        <h2 class="h5 text-primary mb-3">Informations sur l'entreprise</h2>
                        <div class="bg-white p-4 rounded shadow-sm">
                            <h5 class="fw-bold mb-2">{{ $entreprise['nom_entreprise'] ?? 'Nom non disponible' }}</h5>
                            <p class="mb-1"><strong>ID National:</strong> {{ $entreprise['id_national'] ?? '-' }}</p>
                            <p class="mb-1"><strong>Adresse:</strong> {{ $entreprise['adresse'] ?? '-' }}</p>
                            <p class="mb-1"><strong>Ville:</strong> {{ $entreprise['ville'] ?? '-' }}</p>
                            <p class="mb-1"><strong>Code Postal:</strong> {{ $entreprise['code_postal'] ?? '-' }}</p>
                            <p class="mb-1"><strong>Téléphone:</strong> {{ $entreprise['telephone'] ?? '-' }}</p>
                            <p class="mb-0"><strong>Email:</strong> {{ $entreprise['email'] ?? '-' }}</p>
                        </div>
                    </section>
                @endif

                @if(!empty($servicesEntreprise))
                    <section>
                        <h2 class="h5 text-primary mb-3">Autres services de cette entreprise</h2>
                        <div class="list-group shadow-sm rounded">
                            @foreach($servicesEntreprise as $s)
                                @php
                                    $currentServiceId = $service['id'] ?? null;
                                    $otherServiceId = $s['id'];
                                @endphp
                                @if($otherServiceId != $currentServiceId)
                                    <a href="{{ route('details', ['id' => $otherServiceId, 'entreprise_id' => $s['entreprise_id']]) }}"
                                        class="list-group-item list-group-item-action rounded-2">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1 text-primary">{{ $s['designation'] }}</h6>
                                        </div>
                                        <small class="text-muted">{{ \Illuminate\Support\Str::limit($s['description'], 80) }}</small>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>
        </div>

        {{-- Conditions Générales --}}
        <section class="mt-5">
            <h2 class="h5 text-primary mb-3">Conditions Générales</h2>
            <div class="accordion" id="accordionCGU">
                <div class="accordion-item rounded shadow-sm border-0">
                    <h2 class="accordion-header" id="headingCGU">
                        <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseCGU" aria-expanded="true" aria-controls="collapseCGU">
                            Lire les conditions générales du service
                        </button>
                    </h2>
                    <div id="collapseCGU" class="accordion-collapse collapse show" aria-labelledby="headingCGU"
                        data-bs-parent="#accordionCGU">
                        <div class="accordion-body text-secondary" style="line-height: 1.6;">
                            En vous abonnant, vous acceptez nos conditions générales de service.
                            @if(!empty($articlesService))
                                @foreach($articlesService as $article)
                                    <h6 class="fw-bold">{{ $article['titre'] }}</h6>
                                    <p>{{ $article['contenu'] }}</p>
                                    @if(isset($article['lien']))
                                        <img src="{{ $article['lien'] }}" class="img-fluid mb-2">
                                    @endif
                                @endforeach
                            @else
                                <p class="text-muted">Aucune condition spécifique pour ce service.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- Modal d’abonnement --}}
    <div class="modal fade" id="modal-abonnement" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3 shadow">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Confirmation d'abonnement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <form id="subscribeForm" method="POST" action="{{ route('abonnement.creer') }}" novalidate>
                        @csrf
                        <input type="hidden" id="modal-plan-id" name="plan_id" value="">
                        <input type="hidden" id="abonnement_id" name="abonnement_id" value="0">
                        <input type="hidden" id="service_id" name="service_id"
                            value="{{ $service['id'] ?? $service[0]['id'] }}">
                        <input type="hidden" id="modal-prix" name="prix" value="">
                        <input type="hidden" id="modal-interval" name="interval" value="30">
                        <input type="hidden" id="entreprise_id" name="entreprise_id"
                            value="{{ $service['entreprise_id'] ?? $service[0]['entreprise_id'] }}">

                        <div class="form-check mb-4 ps-5">
                            <input class="form-check-input me-2" type="checkbox" id="cguCheckbox" required>
                            <label class="form-check-label fw-semibold" for="cguCheckbox">
                                J'accepte les <a href="#collapseCGU" data-bs-toggle="collapse">conditions générales
                                    d'abonnement</a> et comprends les modalités liées au plan choisi.
                            </label>
                        </div>

                        <div class="modal-footer px-0 border-0">
                            <button type="submit" class="btn btn-success rounded-pill w-100 py-2 fs-5 fw-semibold">
                                Confirmer l'abonnement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @php
        $client = Session::get('user');
    @endphp

    {{-- Chat Sider --}}
    <div id="chatSider" class="chat-sider position-fixed top-2 end-0 bg-white shadow-lg rounded-start"
        style="width: 400px; max-width: 80%; transform: translateX(100%); transition: transform 0.4s; display:flex; flex-direction:column;
                                                            top: 50px; right: 20px; bottom: 80px; height: calc(100vh - 150px); z-index:1050;">
        <div class="d-flex justify-content-between align-items-center p-3 bg-primary text-white rounded-top">
            <h4 class="mb-0 fs-8">Subsure - Assistant Client</h4>
            <button id="closeChat" class="btn-close btn-close-white"></button>
        </div>

        <div class="p-3 flex-grow-1 overflow-auto d-flex flex-column" id="chatMessages" style="gap:8px;">
            <div class="message ai-message p-2 bg-light rounded shadow-sm">
                Bonjour <strong>{{ $client['nom'] ?? '!' }}</strong> ! 👋<br>
                En quoi puis-je vous être utile aujourd'hui ?
            </div>
        </div>

        <div class="p-3 bg-light border-top">
            <form id="chatForm" class="d-flex w-100 gap-2" method="POST" action="{{ route('generate') }}">
                @csrf
                <div class="d-flex w-100 gap-2">
                    <input type="text" id="chatInput" name="prompt" class="form-control rounded-pill flex-grow-1"
                        placeholder="Posez votre question..." required style="height:35px;">
                    <button type="submit" class="btn btn-primary rounded-pill px-4" style="height:35px;">Envoyer</button>
                </div>

            </form>
        </div>
    </div>

    {{-- Bouton pour rouvrir chat --}}
    <button id="openChatBtn"
        class="position-fixed bottom-0 end-0 m-3 shadow-lg d-flex align-items-center justify-content-center"
        style="width:70px; aspect-ratio:1/1; border-radius:50%; background:#ffffff9f; border:4px double #fff; z-index:1060; display:none; cursor:pointer; overflow:hidden; animation:pulse-border 2s infinite linear;">
        <svg xmlns="http://www.w3.org/2000/svg" width="50%" height="50%" fill="currentColor" class="bi bi-chat-dots-fill"
            viewBox="0 0 16 16">
            <path
                d="M16 8c0 3.866-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.584.296-1.925.864-4.181 1.234-.2.032-.352-.176-.273-.362.354-.836.674-1.95.77-2.966C.744 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7M5 8a1 1 0 1 0-2 0 1 1 0 0 0 2 0m4 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2" />
        </svg>
    </button>
    {{-- Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const chatSider = document.getElementById('chatSider');
            const openChatBtn = document.getElementById('openChatBtn');
            const closeChatBtn = document.getElementById('closeChat');
            const chatForm = document.getElementById('chatForm');
            const chatMessages = document.getElementById('chatMessages');
            const chatInput = document.getElementById('chatInput');

            let isOpened = false;

            // Ouvrir le chat automatiquement après 7 secondes
            setTimeout(() => {
                chatSider.style.transform = 'translateX(0)';
                openChatBtn.style.display = 'none';
                isOpened = true;
            }, 7000);

            // Fermer chat
            closeChatBtn.addEventListener('click', () => {
                chatSider.style.transform = 'translateX(100%)';
                openChatBtn.style.display = 'flex';
                isOpened = false;
            });

            // Bouton pour ouvrir ou fermer le chat
            openChatBtn.addEventListener('click', () => {
                if (isOpened) {
                    chatSider.style.transform = 'translateX(100%)';
                    openChatBtn.style.display = 'flex';
                    isOpened = false;
                } else {
                    chatSider.style.transform = 'translateX(0)';
                    openChatBtn.style.display = 'none';
                    isOpened = true;
                }
            });

            // Envoi message
            chatForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const userMessage = chatInput.value.trim();
                if (!userMessage) return;

                // Ajouter message utilisateur
                const userDiv = document.createElement('div');
                userDiv.className = 'message user-message p-2 bg-primary text-white rounded-end shadow-sm align-self-end';
                userDiv.innerHTML = `<strong>Vous :</strong> ${userMessage}`;
                chatMessages.appendChild(userDiv);
                chatMessages.scrollTop = chatMessages.scrollHeight;

                // Envoyer au serveur
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
                        aiDiv.className = 'message ai-message p-2 bg-light rounded-start shadow-sm align-self-start';
                        aiDiv.innerHTML = `<strong>Subsure :</strong> ${data.response}`;
                        chatMessages.appendChild(aiDiv);
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    })
                    .catch(err => console.error(err));

                chatInput.value = '';
            });
        });
    </script>


    {{-- Styles --}}
    <style>
        .chat-sider .message {
            max-width: 85%;
            word-wrap: break-word;
        }

        .user-message {
            text-align: right;
        }

        .ai-message {
            text-align: left;
        }

        @media(max-width:768px) {
            .chat-sider {
                width: 90%;
                top: 60px;
                bottom: 60px;
                height: calc(100vh - 120px);
            }
        }
    </style>



@endsection