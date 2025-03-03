import {
    Livewire,
    Alpine,
} from "../../vendor/livewire/livewire/dist/livewire.esm";

type EditableTextData = {
    edit: boolean;
    text: string;
    savedText: string;
    required: boolean;
    editable: boolean;
};

type DispatchFn = (eventType: string, detail?: unknown) => void;

type AccordionData = {
    openKey: string | undefined;
};

type AccordionItemData = {
    key: string | undefined;
    $dispatch: DispatchFn;
};

document.addEventListener("alpine:init", () => {
    Alpine.data("editableText", (data: EditableTextData) => ({
        ...data,
        _privateEdit: data.edit,
        get edit(): boolean {
            return this._privateEdit && this.editable;
        },
        set edit(value: boolean) {
            if (this.editable) {
                this._privateEdit = value;
            }
        },
        get unchanged(): boolean {
            return this.text === this.savedText;
        },
        save(event: Event, dispatch: DispatchFn) {
            if (this.text.trim() !== "" || !this.required) {
                this.edit = false;
                if (this.text !== this.savedText) {
                    this.savedText = this.text;
                    dispatch("save", {
                        text: this.text,
                    });
                } else {
                    event.stopPropagation();
                }
            }
        },
        cancel(event: Event) {
            if (
                !this.unchanged &&
                !confirm(
                    "You have unsaved changes. Are you sure you want to drop them?",
                )
            ) {
                event.stopPropagation();
                event.preventDefault();
                return;
            }
            this.edit = false;
            this.text = this.savedText;
        },
        get error(): boolean {
            return !this.text.trim() && this.required;
        },
        get rows(): number {
            return this.text.split("\n").length;
        },
    }));
    Alpine.data("accordion", (data: AccordionData) => ({
        ...data,
        isOpen(key: string): boolean {
            return this.openKey === key;
        },
        open(key: string) {
            this.openKey = key;
        },
        toggle(key: string) {
            if (this.openKey === key) {
                this.openKey = undefined;
            } else {
                this.openKey = key;
            }
        },
        reset() {
            this.openKey = undefined;
        },
    }));
    Alpine.data("accordionItem", (data: AccordionItemData) => ({
        __data: data,
        get key() {
            return this.__data.key;
        },
        init($dispatch: DispatchFn) {
            this.__data.$dispatch = $dispatch;
        },
        open($dispatch?: DispatchFn) {
            ($dispatch ?? this.__data.$dispatch)("open", { key: this.key });
        },
        toggle($dispatch?: DispatchFn) {
            ($dispatch ?? this.__data.$dispatch)("toggle", { key: this.key });
        },
    }));
});

Livewire.start();
