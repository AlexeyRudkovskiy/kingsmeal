const tabsTargets = {};

export default function () {

    const tabsContainers = document.querySelectorAll('[data-tabs]');
    let i = 0;
    for (const tabsContainer of tabsContainers) {
        const tabs = tabsContainer.querySelectorAll('[data-target]');
        tabsTargets[i] = {};

        let j = 0;
        for (const tab of tabs) {
            const target = document.querySelector(tab.getAttribute('data-target'));
            tab.setAttribute('data-tab-id', j);
            tab.setAttribute('data-tabs-set-id', i);

            tabsTargets[i][j] = target;

            tab.addEventListener('click', () => {
                const tabsCollectionId = tab.getAttribute('data-tabs-set-id');
                let tabsTarget = tabsTargets[tabsCollectionId];

                for (const targetKey in tabsTarget) {
                    tabsTarget[targetKey].classList.add('hidden');
                    // target.classList.add('hidden');
                }

                if (tab.classList.contains('active')) {
                    for (const _tab of tabs) {
                        _tab.classList.remove('active');
                        _tab.classList.remove('compact');
                    }
                } else {
                    for (const _tab of tabs) {
                        _tab.classList.remove('active');
                        if (!_tab.classList.contains('compact')) {
                            _tab.classList.add('compact');
                        }
                    }

                    tab.classList.add('active');
                    target.classList.remove('hidden');
                }
            });

            j++;
        }

        i++;
    }

}
