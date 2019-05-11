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
        <p class="para-de-querer-copiar-e-colar-seu-puto-e-aprende-que-nao-eh-pra-ficar-reportando-qualquer-um-na-porra-dos-servidores">Por favor digite abaixo e clique em confirmar: <strong>Concordo em melhorar a descrição e precisão de todos os meus futuros reports visando ajudar toda a equipe dos servidores de_nerdTV.</strong></p>
        
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
        
        <p><strong>Você enviou um report incorreto para nossos sistemas, e está temporariamente bloqueado de jogar em todos os nossos servidores até concordar em ser mais criterioso com os reports.</strong></p>
        @auth
            <div>
                <form action="{{ route('my-reports.acked', $report) }}" method="POST" class="form-inline">
                    @csrf
                    <div class="form-group mx-sm-3 mb-2 flex-grow-1">
                        <label for="ack" class="sr-only">Ack</label>
                        <textarea style="height: 84px;" name="confirmation" class="form-control-lg w-100" id="ack" placeholder="Concordo em melhorar a descrição e precisão de todos os meus futuros reports visando ajudar toda a equipe dos servidores de_nerdTV."></textarea>
                    </div>
                    <button type="submit" class="btn btn-lg btn-primary mb-2">Confirmar</button>
                </form>
            </div>
        @endauth
        @guest
            <h1 class="text-center">Por favor faça o login para realizar o processo de desbloqueio!</h1>
        @endguest
    </div>
</div>
