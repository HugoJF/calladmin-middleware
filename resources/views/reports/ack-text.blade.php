<div class="row border rounded bg-light p-3 mb-3">
    <div class="col">
        <h3 class="d-block mb-4">Por favor leia todo o texto abaixo antes de continuar:</h3>
        <p class="text-muted">Sem pressa, você terá que esperar 1min nessa página antes de aceitar.</p>

        <h4 class="mt-5">► O que é isso?</h4>
        <p>Visando melhorar a experiência de jogo em todos os nossos servidores, <strong>estamos implementando novas regras e protocolos em nosso sistema de report</strong> com objetivo de reduzir a quantidade de falsos-negativos e permitir que nossa equipe tenha o menor tempo de resposta possível.</p>

        <p>Começando em Maio de 2019, <strong>todos os jogadores que enviarem reports incorretos</strong>, terão que passar por essa confirmação em nosso sistema <strong>para CADA report incorreto submetido ao sistema.</strong></p>

        <h4 class="mt-5">► Por quê?</h4>
        <p>
            Com o constante crescimento de nossa rede de servidores, encontramos um grande desafio: <strong>lidar com a quantidade de reports</strong>.
            Esse sistema de report foi desenvolvido para facilitar todo o processo de report e garantir que <strong>nenhum</strong> report seja ignorado.
            Contudo, com 583 reports
            <small class="text-muted">(no momento da escrita)</small>
            , <strong>apenas 12.3% foram avaliados como corretos</strong>.
        </p>
        <p>
            O objetivo de bloquear o jogador temporariamente é <strong>criar uma penalidade associada ao uso incorreto da ferramente de reports de forma a desestimular reports sem motivo</strong>
        </p>

        <h4 class="mt-5">► O que devo fazer para poder jogar novamente?</h4>
        <p class="para-de-querer-copiar-e-colar-seu-puto-e-aprende-que-nao-eh-pra-ficar-reportando-qualquer-um-na-porra-dos-servidores">Por favor digite abaixo e clique em confirmar: <strong>{{ \App\Http\Controllers\MyReportsController::CONFIRMATION_TEXT }}</strong></p>

        <h4 class="mt-5">► Nosso objetivo</h4>
        <ul>
            <li>Reduzir a <strong>quantidade de reports errados</strong> no sistema;</li>
            <li>Garantir <strong>menor tempo de resposta possível</strong> de nossos moderadores;</li>
            <li>Tornar nossos <strong>reports mais úteis</strong> para outros servidores;</li>
            <li><strong>Melhorar a <i>quality-of-life</i></strong> de todos os nossos servidores.</li>
        </ul>

        <h4 class="mt-4">► Como posso melhorar</h4>
        <p>É extremamente simples:</p>
        <ul>
            <li>Adicione uma <strong>boa descrição</strong> no seu report;</li>
            <li>Se estiver na dúvida, <strong>DEIXE CLARO NO MOTIVO DO REPORT</strong>;</li>
            <li><strong>Analise o jogador que você está incomodado</strong> antes de reportar;</li>
            <li>Não reporte ninguém <strong>se estiver irritado</strong> com o jogo.</li>
        </ul>
        <p>Caso tenha interesse na lógica atrás dessa ferramenta e detalhes sobre os desafios de hospedar servidores de CS:GO, <a href="https://denerdtv.com/confirmacoes-de-reports-errados">clique aqui para ler nosso post a respeito disso.</a></p>

        <h4 class="mt-4">► TL;DR</h4>

        <p>Você enviou um report incorreto para nossos sistemas, e está temporariamente bloqueado de jogar em todos os nossos servidores até concordar em ser mais criterioso com os reports.</p>

        <p><strong>Digite o texto abaixo no campo de verificação:</strong></p>

        <div x-data="differ()" x-init="update()">
            <div class="para-de-querer-copiar-e-colar-seu-puto-e-aprende-que-nao-eh-pra-ficar-reportando-qualquer-um-na-porra-dos-servidores">
                <pre class="d-flex justify-content-center">
                    <template x-for="part in parts">
                        <span
                            x-text="part.text"
                            :class="{
                                'text-success': part.type,
                                'font-weight-bold text-danger': !part.type,
                            }"
                        ></span>
                    </template>
                </pre>
            </div>

            <p class="text-center font-weight-bold" x-show="value === original">Obrigado :)</p>

            @auth
                <form action="{{ route('my-reports.acked', $report) }}" method="POST" class="form-inline">
                    @csrf
                    <div class="form-group mx-sm-3 mb-2 flex-grow-1">
                        <label for="ack" class="sr-only">Ack</label>
                        <textarea
                            x-on:keyup="update()"
                            x-model="value"
                            style="height: 84px;"
                            name="confirmation"
                            class="form-control-lg w-100"
                            id="ack"
                            placeholder="{{ \App\Http\Controllers\MyReportsController::CONFIRMATION_TEXT }}"
                        ></textarea>
                    </div>
                    <button
                        :disabled="value !== original"
                        :class="{
                            'btn-success': value === original,
                            'btn-outline-dark': value !== original
                        }"
                        type="submit"
                        class="btn btn-lg mb-2"
                    >Confirmar
                    </button>
                </form>
            @endauth
        </div>
        @guest
            <h1 class="text-center">Por favor faça o login para realizar o processo de desbloqueio!</h1>
        @endguest
    </div>
</div>

@push('scripts')
    <script type="application/javascript">
        console.log('scripts');

        function differ() {
            return {
                value: "",
                original: "{{ \App\Http\Controllers\MyReportsController::CONFIRMATION_TEXT }}",
                parts: [],
                update() {
                    let original = this.original;
                    let comp = this.value;
                    let ref = original.split("").map((val, i) => comp[i] === val);

                    this.parts = [];

                    for (let i = 0; i < original.length; i++) {
                        if (i === 0 || ref[i - 1] !== ref[i]) {
                            this.parts.push({
                                text: "",
                                type: ref[i]
                            });
                        }
                        this.parts[this.parts.length - 1].text += original[i];
                    }
                }
            };
        }
    </script>
@endpush
